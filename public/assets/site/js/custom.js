(function(){
    function getId(url){const m=String(url).match(/(?:v=|youtu\.be\/)([^&#?/]+)/);return m?m[1]:null;}
    function openYT(id){
        const wrap=document.createElement('div');
        wrap.className='yt-modal';
        wrap.innerHTML=`
      <div class="yt-backdrop"></div>
      <div class="yt-dialog"><button class="yt-close">&times;</button>
        <div class="yt-frame-wrap">
          <iframe src="https://www.youtube.com/embed/${id}?autoplay=1&rel=0"
                  allow="autoplay;encrypted-media" allowfullscreen></iframe>
        </div>
      </div>`;
        document.body.appendChild(wrap);
        const close=()=>wrap.remove();
        wrap.querySelector('.yt-backdrop').onclick=close;
        wrap.querySelector('.yt-close').onclick=close;
        document.addEventListener('keydown',e=>{if(e.key==='Escape')close();});
    }
    document.addEventListener('click',e=>{
        const a=e.target.closest('.js-youtube');
        if(!a)return;
        e.preventDefault();
        const id=a.dataset.yt||getId(a.href);
        if(id)openYT(id);
    });



})();
