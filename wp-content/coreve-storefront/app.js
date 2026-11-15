'use strict';
(()=>{
const $=(s,r=document)=>r.querySelector(s), $$=(s,r=document)=>[...r.querySelectorAll(s)];
let lastFocus=null;
function openOverlay(el){lastFocus=document.activeElement;el.classList.add('open');document.body.classList.add('locked');const first=$('button,a,input',el);if(first)first.focus();}
function closeOverlay(el){el.classList.remove('open');document.body.classList.remove('locked');if(lastFocus)lastFocus.focus();}
$$('.size-guide-open').forEach(b=>b.onclick=()=>openOverlay($('#size-overlay')));
$$('[data-close]').forEach(b=>b.onclick=()=>closeOverlay(b.closest('.overlay')));
$$('.overlay').forEach(o=>o.addEventListener('click',e=>{if(e.target===o)closeOverlay(o);}));
const menu=$('#mobile-menu');const menuToggle=$('.menu-toggle');menuToggle?.setAttribute('aria-expanded','false');
function closeMenu(){menu.classList.remove('open');document.body.classList.remove('locked');menuToggle?.setAttribute('aria-expanded','false');menuToggle?.focus();}
menuToggle&&(menuToggle.onclick=()=>{menu.classList.add('open');document.body.classList.add('locked');menuToggle.setAttribute('aria-expanded','true');$('.menu-close').focus();});$('.menu-close').onclick=closeMenu;$$('nav a',menu).forEach(a=>a.onclick=closeMenu);
document.addEventListener('keydown',e=>{const active=$('.overlay.open')||$('.mobile-menu.open');if(!active)return;if(e.key==='Escape'){active===menu?closeMenu():closeOverlay(active);}if(e.key==='Tab'){const controls=$$('button,a,input,select,[tabindex="0"]',active).filter(el=>!el.disabled&&el.getClientRects().length);const first=controls[0],last=controls.at(-1);if(e.shiftKey&&document.activeElement===first){e.preventDefault();last?.focus();}else if(!e.shiftKey&&document.activeElement===last){e.preventDefault();first?.focus();}}});
if($('#main-photo')){$$('.gallery-thumb').forEach(b=>b.onclick=()=>{const im=$('#main-photo');im.srcset=b.dataset.responsive||'';im.src=b.dataset.src;im.dataset.zoom=b.dataset.zoom||b.dataset.src;im.alt=b.querySelector('img').alt;$('.gallery-main').classList.toggle('contain',b.dataset.contain==='true');$$('.gallery-thumb').forEach(x=>x.classList.toggle('active',x===b));});$('.gallery-main').onclick=()=>{const im=$('#zoom-overlay img');im.src=$('#main-photo').dataset.zoom||$('#main-photo').src;im.classList.remove('magnified');im.alt=$('#main-photo').alt;openOverlay($('#zoom-overlay'));};}

const modelScenes=$$('.model-scene');
const rail=$('.progress-rail'),dock=$('.collection-dock');
if(modelScenes.length){
 const links=$$('a',rail);
 const syncScene=()=>{const y=innerHeight*.5;let active=-1;modelScenes.forEach((s,i)=>{const r=s.getBoundingClientRect();if(r.top<=y&&r.bottom>y)active=i;});rail.classList.toggle('visible',active>=0);dock.classList.toggle('visible',active>=0);links.forEach((a,i)=>{a.classList.toggle('active',i===active);if(i===active)a.setAttribute('aria-current','true');else a.removeAttribute('aria-current');});};
 let scheduled=false;addEventListener('scroll',()=>{if(!scheduled){requestAnimationFrame(()=>{syncScene();scheduled=false;});scheduled=true;}},{passive:true});addEventListener('resize',syncScene);syncScene();
 // The floating dock links directly to the active product.
 $$('#collection-overlay nav a').forEach(a=>a.addEventListener('click',()=>closeOverlay($('#collection-overlay'))));
 const motion=$('.hero-motion');motion.onclick=()=>{const paused=document.body.classList.toggle('motion-paused');motion.textContent=paused?'Play motion':'Pause motion';motion.setAttribute('aria-pressed',String(paused));};
 if(matchMedia('(prefers-reduced-motion: reduce)').matches){motion.hidden=true;document.body.classList.add('motion-paused');}
}

})();

const zoomPhoto=document.querySelector("#zoom-overlay img");if(zoomPhoto)zoomPhoto.onclick=()=>zoomPhoto.classList.toggle("magnified");document.querySelectorAll("video").forEach(v=>{new IntersectionObserver(es=>{if(!es[0].isIntersecting)v.pause();}).observe(v);});

// Monthly editorial label, using the brand's India timezone.
function coreveDropLabel(date=new Date()){
 const parts=new Intl.DateTimeFormat('en-GB',{timeZone:'Asia/Kolkata',month:'long',year:'2-digit'}).formatToParts(date);
 return parts.find(p=>p.type==='month').value+" '"+parts.find(p=>p.type==='year').value+' Drop';
}
function updateCoreveDrop(){document.querySelectorAll('[data-drop-label]').forEach(el=>{el.textContent=coreveDropLabel();});}
updateCoreveDrop();setInterval(updateCoreveDrop,60000);
document.addEventListener('visibilitychange',()=>{if(!document.hidden)updateCoreveDrop();});
window.addEventListener('pageshow',updateCoreveDrop);

// Load the campaign film only at desktop widths. Autoplay is silent.
(()=>{
 const video=document.querySelector('.desktop-hero-video');if(!video)return;
 const desktop=matchMedia('(min-width:701px)'),reduced=matchMedia('(prefers-reduced-motion: reduce)');
 const motion=document.querySelector('.hero-motion');let visible=true;
 function syncHeroVideo(){
  const selectedSource=desktop.matches?video.dataset.videoSrc:video.dataset.mobileVideoSrc;const selectedPoster=desktop.matches?video.dataset.posterSrc:video.dataset.mobilePosterSrc;if(video.hasAttribute('src')&&video.getAttribute('src')!==selectedSource){video.pause();video.removeAttribute('src');video.load();}video.poster=selectedPoster;
  video.muted=true;
  // Responsive poster is selected above.
  const paused=document.body.classList.contains('motion-paused');
  if(paused||document.hidden||!visible){video.pause();return;}
  if(!video.hasAttribute('src'))video.src=selectedSource;
  video.play().catch(error=>{if(error.name==='AbortError'||!visible||document.hidden||!video.hasAttribute('src'))return;document.body.classList.add('motion-paused');if(motion){motion.textContent='Play motion';motion.setAttribute('aria-pressed','true');}});
 }
 if(motion){if(reduced.matches){motion.hidden=false;motion.textContent='Play motion';motion.setAttribute('aria-pressed','true');}motion.addEventListener('click',syncHeroVideo);}
 desktop.addEventListener('change',syncHeroVideo);
 reduced.addEventListener('change',()=>{if(reduced.matches){document.body.classList.add('motion-paused');if(motion){motion.hidden=false;motion.textContent='Play motion';motion.setAttribute('aria-pressed','true');}}syncHeroVideo();});
 document.addEventListener('visibilitychange',syncHeroVideo);
 new IntersectionObserver(entries=>{visible=entries[0].isIntersecting;syncHeroVideo();},{threshold:0}).observe(video.closest('.hero-film'));
 syncHeroVideo();
})();

// Native sticky scenes provide the roll-up reveal; transforms follow scroll.
(()=>{
 const sequence=document.querySelector('.model-sequence');if(!sequence)return;
 const scenes=[...sequence.querySelectorAll('.model-scene')];
 const reduced=matchMedia('(prefers-reduced-motion: reduce)');
 const clamp=n=>Math.max(0,Math.min(1,n));let queued=false;
 scenes.forEach((scene,i)=>scene.style.setProperty('--scene-order',String(i+1)));
 function paintRollingScenes(){
  queued=false;if(reduced.matches)return;
  const header=document.querySelector('.header').getBoundingClientRect().height;
  const start=sequence.getBoundingClientRect().top+scrollY;
  let offset=0;
  for(const scene of scenes){
   const height=scene.offsetHeight;
   const progress=(scrollY+header-start-offset)/height;
   const enter=clamp(progress+1),exit=clamp(progress);
   scene.style.setProperty('--roll-lift',(-40*exit+24*(1-enter)).toFixed(2)+'px');
   scene.style.setProperty('--roll-turn',(-4*exit+3*(1-enter)).toFixed(2)+'deg');
   scene.style.setProperty('--roll-scale',(1-.055*exit+.025*(1-enter)).toFixed(4));
   scene.style.setProperty('--roll-letter',(-28*exit+18*(1-enter)).toFixed(2)+'px');
   scene.style.setProperty('--roll-copy',String(1-clamp((exit-.45)/.45)));
   offset+=height;
  }
 }
 function scheduleRollingScenes(){if(!queued){queued=true;requestAnimationFrame(paintRollingScenes);}}
 addEventListener('scroll',scheduleRollingScenes,{passive:true});addEventListener('resize',scheduleRollingScenes);
 reduced.addEventListener('change',()=>{if(reduced.matches)scenes.forEach(scene=>['--roll-lift','--roll-turn','--roll-scale','--roll-letter','--roll-copy'].forEach(p=>scene.style.removeProperty(p)));scheduleRollingScenes();});
 paintRollingScenes();
})();

// Product jumps must use each scene's natural position, not its pinned position.
(()=>{
 const sequence=document.querySelector('.model-sequence');if(!sequence)return;
 const scenes=[...sequence.querySelectorAll('.model-scene')];
 function jumpToScene(hash,smooth){
  let id;try{id=decodeURIComponent(hash.slice(1));}catch{return false;}
  const index=scenes.findIndex(s=>s.id===id);if(index<0)return false;
  const start=sequence.getBoundingClientRect().top+scrollY;
  const offset=scenes.slice(0,index).reduce((sum,s)=>sum+s.offsetHeight,0);
  const header=document.querySelector('.header').getBoundingClientRect().height;
  const reduced=matchMedia('(prefers-reduced-motion: reduce)').matches;
  const gap=reduced?parseFloat(getComputedStyle(scenes[0]).marginBottom)||0:0;
  scrollTo({top:start+offset+gap*index-header,behavior:'instant'});
  return true;
 }
 document.querySelectorAll('a[href*="#model-"]').forEach(a=>a.addEventListener('click',event=>{
  const u=new URL(a.href,location.href);if(u.origin!==location.origin||u.pathname!==location.pathname)return;
  if(event.metaKey||event.ctrlKey||event.shiftKey||event.altKey)return;
  if(jumpToScene(u.hash,true)){event.preventDefault();history.replaceState(null,'',u.hash);}
 }));
 if(location.hash.startsWith('#model-'))requestAnimationFrame(()=>jumpToScene(location.hash,false));
 addEventListener('hashchange',()=>{if(location.hash.startsWith('#model-'))jumpToScene(location.hash,false);});
})();

// Keep the floating Discover more link aligned with the foreground scene.
(()=>{
 const dock=document.querySelector('a.collection-dock');if(!dock)return;
 const scenes=[...document.querySelectorAll('.model-scene')];let queued=false;
 function syncProductDock(){
  queued=false;const midpoint=innerHeight*.5;let current=null;
  for(const scene of scenes){const b=scene.getBoundingClientRect();if(b.top<=midpoint&&b.bottom>midpoint)current=scene;}
  if(!current)return;
  const link=current.querySelector('a.discover'),name=current.querySelector('h2');if(!link)return;
  dock.href=link.href;dock.setAttribute('aria-label','Discover more about '+name.textContent.trim());
 }
 function scheduleProductDock(){if(!queued){queued=true;requestAnimationFrame(syncProductDock);}}
 addEventListener('scroll',scheduleProductDock,{passive:true});addEventListener('resize',scheduleProductDock);addEventListener('hashchange',scheduleProductDock);syncProductDock();
})();

const originalZoomImage=document.querySelector("#zoom-overlay img");if(originalZoomImage)originalZoomImage.addEventListener("load",()=>originalZoomImage.style.setProperty("--zoom-native-width",originalZoomImage.naturalWidth+"px"));

// Keep previously saved preview-bag thumbnails aligned with live gallery images.
(()=>{
 const im=document.querySelector('#zoom-overlay img');if(im){im.tabIndex=0;im.setAttribute('role','button');im.setAttribute('aria-label','Toggle full-size product image');im.setAttribute('aria-pressed','false');im.addEventListener('load',()=>im.setAttribute('aria-pressed',String(im.classList.contains('magnified'))));im.addEventListener('click',()=>im.setAttribute('aria-pressed',String(im.classList.contains('magnified'))));im.addEventListener('keydown',e=>{if(e.key==='Enter'||e.key===' '){e.preventDefault();im.click();}});}
})();
