#!/usr/bin/env python3
# -*- coding: utf-8 -*-

"""
xlsx_to_mysql.py (Laravel-first, MariaDB/MySQL compatible)
- يفترض أن Laravel أنشأ جدول `advertisements` والفهارس.
- يقرأ XLSX، ينظّف القيم (نصوص/تواريخ)، يزيل التكرارات داخل الملف.
- يكتب إلى MySQL/MariaDB باستخدام UPSERT:
  * MySQL 8.0.20+:  INSERT ... AS new ON DUPLICATE KEY UPDATE
  * MariaDB:        INSERT ... ON DUPLICATE KEY UPDATE col=VALUES(col)
- يضبط AUTO_INCREMENT = MAX(ID_ADVER)+1 في النهاية (مهم لو تعمل migrate:refresh).
"""

import os
import sys
import pandas as pd
from sqlalchemy import create_engine, text
from sqlalchemy.exc import SQLAlchemyError

# ============ CONFIG ============
XLSX_PATH = "Job (1).xlsx"     # مسار ملف الإكسل
CSV_PATH  = "jobs_export.csv"  # نسخة أرشيفية

DB_USER = "root"
DB_PASS = ""                   # كلمة المرور
DB_HOST = "localhost"
DB_PORT = 3306
DB_NAME = "gedco"
TABLE_NAME = "advertisements"

CHUNKSIZE = 500
DATE_COLS = ["DATE_NEWS", "INSERT_DATE", "UPDATE_DATE", "DATE_NEWS1"]

UPSERT = True  # خلّيها True عادةً
# ===============================


def engine():
    uri = f"mysql+pymysql://{DB_USER}:{DB_PASS}@{DB_HOST}:{DB_PORT}/{DB_NAME}?charset=utf8mb4"
    return create_engine(uri, pool_pre_ping=True, future=True)


def assert_table_and_columns(conn) -> None:
    """يتأكد أن الجدول موجود وأن الأعمدة الأساسية موجودة (Laravel مسؤول عن الإنشاء)."""
    needed = {
        "ID_ADVER","TITLE","TITLE_E","DATE_NEWS","BODY","BODY_E","PDF",
        "INSERT_USER","UPDATE_USER","INSERT_DATE","UPDATE_DATE","WORD","DATE_NEWS1"
    }

    cols = conn.execute(text("""
        SELECT COLUMN_NAME
        FROM information_schema.columns
        WHERE table_schema = :db AND table_name = :tbl
    """), {"db": DB_NAME, "tbl": TABLE_NAME}).scalars().all()

    if not cols:
        raise RuntimeError(
            f"Table `{TABLE_NAME}` غير موجود في قاعدة `{DB_NAME}`. "
            "شغّل Laravel migrations أولاً."
        )

    missing = needed - set(cols)
    if missing:
        raise RuntimeError(
            f"أعمدة ناقصة في `{TABLE_NAME}`: {', '.join(sorted(missing))}. "
            "تأكد أن المايجريشن أحدث نسخة."
        )


def parse_date_column(series: pd.Series) -> pd.Series:
    """محاولات متعددة لتحويل التواريخ."""
    s = series.astype(str).str.strip()
    s = s.replace({'': pd.NA, 'nan': pd.NA, 'NaT': pd.NA, '<NA>': pd.NA})

    # AM/PM أولاً
    dt = pd.to_datetime(s, format='%m/%d/%Y %I:%M %p', errors='coerce')

    mask = dt.isna() & s.notna()
    if mask.any():
        # صيَغ ISO/عامة
        dt.loc[mask] = pd.to_datetime(s.loc[mask], errors='coerce', dayfirst=False)

    still = dt.isna() & s.notna()
    if still.any():
        # dayfirst احتياط
        dt.loc[still] = pd.to_datetime(s.loc[still], errors='coerce', dayfirst=True)

    return dt


def normalize_users(df: pd.DataFrame) -> pd.DataFrame:
    """تنظيف مستخدمَي الإدخال/التحديث والإكمال المتبادل."""
    for col in ["INSERT_USER","UPDATE_USER"]:
        if col in df.columns:
            df[col] = df[col].astype("string")
            df[col] = df[col].str.strip()
            df[col].replace({'': pd.NA, 'nan': pd.NA, 'NaT': pd.NA, '<NA>': pd.NA}, inplace=True)
    if {'INSERT_USER','UPDATE_USER'}.issubset(df.columns):
        df['INSERT_USER'] = df['INSERT_USER'].fillna(df['UPDATE_USER'])
        df['UPDATE_USER'] = df['UPDATE_USER'].fillna(df['INSERT_USER'])
    return df


def load_excel_and_fix(xlsx_path: str) -> pd.DataFrame:
    print(f"[+] Reading Excel: {xlsx_path}")
    df = pd.read_excel(xlsx_path, engine="openpyxl")
    df.columns = [str(c).strip() for c in df.columns]

    # تحقق العمود الإلزامي
    if 'ID_ADVER' not in df.columns:
        raise KeyError("الملف يفتقد العمود الإلزامي ID_ADVER")

    # تنظيف نصوص عامة
    for col in ["TITLE","TITLE_E","BODY","BODY_E","PDF","WORD","INSERT_USER","UPDATE_USER"]:
        if col in df.columns:
            df[col] = df[col].astype("string")
            df[col] = df[col].str.strip()
            df[col].replace({'': pd.NA, 'nan': pd.NA, 'NaT': pd.NA, '<NA>': pd.NA}, inplace=True)

    # IDs
    df['ID_ADVER'] = pd.to_numeric(df['ID_ADVER'], errors='coerce').astype('Int64')
    before = len(df)
    df = df[df['ID_ADVER'].notna()]
    df['ID_ADVER'] = df['ID_ADVER'].astype(int)
    after = len(df)
    if after < before:
        print(f"[!] Dropped {before - after} row(s) without valid ID_ADVER")

    # Dates
    for c in DATE_COLS:
        if c in df.columns:
            df[c] = parse_date_column(df[c])

    # Users
    df = normalize_users(df)

    # Deduplicate داخل الملف: احتفظ بآخر تحديث
    sort_keys = [k for k in ["UPDATE_DATE","INSERT_DATE"] if k in df.columns]
    if sort_keys:
        df = df.sort_values(['ID_ADVER'] + sort_keys, ascending=True)
    df = df.drop_duplicates(subset=['ID_ADVER'], keep='last')

    # جهّز None بدل القيم المفقودة
    df = df.where(pd.notnull(df), None)

    return df


def save_csv(df: pd.DataFrame, csv_path: str):
    df.to_csv(csv_path, index=False, encoding='utf-8')
    print(f"[+] CSV saved: {csv_path}")


def is_mariadb(conn) -> bool:
    ver = conn.execute(text("SELECT VERSION()")).scalar() or ""
    return "MariaDB" in ver


def upsert_dataframe(conn, df: pd.DataFrame):
    if not UPSERT:
        raise RuntimeError("UPSERT معطّل. فعّله أو استخدم append مخصص.")

    cols = [
        "ID_ADVER","TITLE","TITLE_E","DATE_NEWS","BODY","BODY_E","PDF",
        "INSERT_USER","UPDATE_USER","INSERT_DATE","UPDATE_DATE","WORD","DATE_NEWS1"
    ]
    # أعمدة ناقصة في الملف—كمّل بـ None كي لا يفشل الإدخال
    for c in cols:
        if c not in df.columns:
            df[c] = None

    # تنظيف محتمل لقيم '<NA>' لو تسرّبت
    df = df.replace({'<NA>': None})

    placeholders = ",".join([f":{c}" for c in cols])

    if is_mariadb(conn):
        # MariaDB: VALUES() مقبولة
        update_clause = ",".join([f"{c}=VALUES({c})" for c in cols if c != "ID_ADVER"])
        sql = text(f"""
            INSERT INTO `{TABLE_NAME}` ({", ".join(cols)})
            VALUES ({placeholders})
            ON DUPLICATE KEY UPDATE {update_clause}
        """)
    else:
        # MySQL 8.0.20+: بدون VALUES() (deprecated)
        update_clause = ",".join([f"{c}=new.{c}" for c in cols if c != "ID_ADVER"])
        sql = text(f"""
            INSERT INTO `{TABLE_NAME}` ({", ".join(cols)})
            VALUES ({placeholders})
            AS new
            ON DUPLICATE KEY UPDATE {update_clause}
        """)

    total = len(df)
    print(f"[+] Upserting {total} row(s) in chunks of {CHUNKSIZE} …")
    for start in range(0, total, CHUNKSIZE):
        chunk = df.iloc[start:start+CHUNKSIZE]
        conn.execute(sql, chunk.to_dict(orient="records"))
    print("[✓] Upsert finished.")


def set_auto_increment(conn):
    """يضبط AUTO_INCREMENT = MAX(ID_ADVER)+1 (MySQL/MariaDB)."""
    print("[+] Setting AUTO_INCREMENT to MAX(ID_ADVER)+1 …")
    conn.execute(text("""
        SET @next_id := (SELECT COALESCE(MAX(ID_ADVER),0) + 1 FROM advertisements);
    """))
    conn.execute(text("""
        SET @sql := CONCAT('ALTER TABLE advertisements AUTO_INCREMENT = ', @next_id);
    """))
    conn.execute(text("PREPARE stmt FROM @sql"))
    conn.execute(text("EXECUTE stmt"))
    conn.execute(text("DEALLOCATE PREPARE stmt"))
    print("[✓] AUTO_INCREMENT updated.")


def main():
    if not os.path.exists(XLSX_PATH):
        print(f"File not found: {XLSX_PATH}")
        sys.exit(1)

    # 1) تحميل ومعالجة
    df = load_excel_and_fix(XLSX_PATH)

    # 2) حفظ CSV للأرشفة
    save_csv(df, CSV_PATH)

    # 3) اتصال + تحقق من وجود الجدول والأعمدة (Laravel مسؤول عن الإنشاء)
    try:
        eng = engine()
        with eng.begin() as conn:
            assert_table_and_columns(conn)
            # 4) كتابة (UPSERT)
            upsert_dataframe(conn, df)
            # 5) ضبط AUTO_INCREMENT بعد الإدخال
            set_auto_increment(conn)
        print("✅ Done.")
    except SQLAlchemyError as e:
        print("DB Error:", e)
        sys.exit(2)
    except Exception as e:
        print("Error:", e)
        sys.exit(3)


if __name__ == "__main__":
    main()