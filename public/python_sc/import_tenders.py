# import_tenders.py
import os
import argparse
from datetime import datetime
import pandas as pd
import mysql.connector

# ===== CLI args =====
parser = argparse.ArgumentParser(description="Create/Import tenders table from Excel.")
parser.add_argument("--file", "-f", default=None, help="Path to Excel file (default: tender.xlsx beside this script)")
parser.add_argument("--sheet", "-s", default="M_TENDER", help="Worksheet name (default: M_TENDER)")
parser.add_argument("--batch", "-b", type=int, default=1000, help="Batch size (default: 1000)")
parser.add_argument("--init-only", action="store_true", help="Create table and exit (no import)")
parser.add_argument("--truncate", action="store_true", help="TRUNCATE table before import")
args = parser.parse_args()

# ===== DB config (ENV first, then fallback) =====
DB_HOST = os.getenv("DB_HOST", "127.0.0.1")
DB_PORT = int(os.getenv("DB_PORT", "3306"))
DB_NAME = os.getenv("DB_NAME", "gedco")
DB_USER = os.getenv("DB_USER", "root")
DB_PASS = os.getenv("DB_PASS", "")
DB_CHARSET = os.getenv("DB_CHARSET", "utf8mb4")
DB_COLLATE = os.getenv("DB_COLLATE", "utf8mb4_general_ci")

# ===== Files =====
BASE_DIR = os.path.dirname(__file__)
EXCEL_PATH = args.file or os.path.join(BASE_DIR, "tender.xlsx")
SHEET_NAME = args.sheet
BATCH_SIZE = args.batch

# رؤوس مطلوبة (مع بدائل محتملة للأخطاء الشائعة)
ALIASES = {
    "mnews_id":      ["MNEWS_ID"],
    "column_name_1": ["COLUMN_NAME_1", "COLUMN_NAME", "COLUMN_NAM"],
    "old_value_1":   ["OLD_VALUE_1", "OLD_VALUE"],
    "new_value_1":   ["NEW_VALUE_1", "NEW_VALUE"],
    "the_date_1":    ["THE_DATE_1", "DATE_1", "DATE"],
    "event_1":       ["EVENT_1", "EVENT"],
    "the_user_1":    ["THE_USER_1", "USER_1", "USER"],
    "coulm_serial":  ["COULM_SERIAL", "COLUMN_SERIAL"],
}

def get_conn():
    return mysql.connector.connect(
        host=DB_HOST, port=DB_PORT,
        user=DB_USER, password=DB_PASS, database=DB_NAME,
        charset=DB_CHARSET, collation=DB_COLLATE
    )

CREATE_SQL = """
CREATE TABLE IF NOT EXISTS `tenders` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `mnews_id` INT NULL,
  `column_name_1` VARCHAR(255) NULL,
  `old_value_1` LONGTEXT NULL,
  `new_value_1` LONGTEXT NULL,
  `the_date_1` VARCHAR(50) NULL,
  `event_1` VARCHAR(255) NULL,
  `the_user_1` VARCHAR(255) NULL,
  `coulm_serial` INT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_mnews_id` (`mnews_id`),
  KEY `idx_the_date_1` (`the_date_1`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
"""

def ensure_table_exists(conn):
    cur = conn.cursor()
    cur.execute(CREATE_SQL)
    conn.commit()
    cur.close()

def truncate_table(conn):
    cur = conn.cursor()
    cur.execute("TRUNCATE TABLE `tenders`")
    conn.commit()
    cur.close()

def resolve_headers(df):
    cols = list(df.columns)
    out = {}
    for target, cands in ALIASES.items():
        picked = None
        for c in cands:
            if c in cols:
                picked = c; break
            # تجاهل حالة الأحرف/المسافات
            for real in cols:
                if str(real).strip().lower() == str(c).strip().lower():
                    picked = real; break
            if picked: break
        out[target] = picked
    return out

def insert_batch(conn, rows):
    if not rows: return 0
    now = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    cols = ["mnews_id","column_name_1","old_value_1","new_value_1","the_date_1","event_1","the_user_1","coulm_serial","created_at","updated_at"]
    sql = f"INSERT INTO `tenders` (`{'`,`'.join(cols)}`) VALUES ({','.join(['%s']*len(cols))})"
    data = []
    for r in rows:
        data.append((
            r.get("mnews_id"),
            r.get("column_name_1"),
            r.get("old_value_1"),
            r.get("new_value_1"),
            r.get("the_date_1"),
            r.get("event_1"),
            r.get("the_user_1"),
            r.get("coulm_serial"),
            now, now
        ))
    cur = conn.cursor()
    cur.executemany(sql, data)
    conn.commit()
    cur.close()
    return len(rows)

def import_excel():
    if not os.path.isfile(EXCEL_PATH):
        print(f"[!] File not found: {EXCEL_PATH}")
        return

    print(f"[*] Reading: {EXCEL_PATH} (sheet={SHEET_NAME}) ...")
    df = pd.read_excel(EXCEL_PATH, sheet_name=SHEET_NAME, dtype=object)
    print(f"[*] Rows found: {len(df)}")

    hdr = resolve_headers(df)
    if not any(hdr.values()):
        print("[!] None of the expected headers found.")
        print("    Expected:", ALIASES)
        print("    Got:", list(df.columns))
        return

    conn = get_conn()
    ensure_table_exists(conn)

    if args.truncate:
        print("[*] Truncating table `tenders` ...")
        truncate_table(conn)

    batch, inserted = [], 0
    for _, row in df.iterrows():
        rec = {
            "mnews_id":      row.get(hdr["mnews_id"])      if hdr["mnews_id"]      else None,
            "column_name_1": row.get(hdr["column_name_1"]) if hdr["column_name_1"] else None,
            "old_value_1":   row.get(hdr["old_value_1"])   if hdr["old_value_1"]   else None,
            "new_value_1":   row.get(hdr["new_value_1"])   if hdr["new_value_1"]   else None,
            "the_date_1":    row.get(hdr["the_date_1"])    if hdr["the_date_1"]    else None,
            "event_1":       row.get(hdr["event_1"])       if hdr["event_1"]       else None,
            "the_user_1":    row.get(hdr["the_user_1"])    if hdr["the_user_1"]    else None,
            "coulm_serial":  row.get(hdr["coulm_serial"])  if hdr["coulm_serial"]  else None,
        }

        # تجاهل الصف الفاضي تمامًا
        if all(v is None or (isinstance(v, float) and pd.isna(v)) for v in rec.values()):
            continue

        batch.append(rec)
        if len(batch) >= BATCH_SIZE:
            inserted += insert_batch(conn, batch)
            print(f"    [+] Inserted: {inserted}")
            batch = []

    if batch:
        inserted += insert_batch(conn, batch)

    conn.close()
    print(f"[✓] Done. Inserted {inserted} rows into `tenders` (id auto-increment).")

def init_only():
    conn = get_conn()
    ensure_table_exists(conn)
    conn.close()
    print("[✓] Table `tenders` is ready (created if missing).")

if __name__ == "__main__":
    if args.init_only:
        init_only()
    else:
        import_excel()