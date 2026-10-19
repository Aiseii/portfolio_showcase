// Browser-side checks are complemented by PHP checks on the server.
document.querySelectorAll('form.validate').forEach(form => {
  form.addEventListener('submit', event => {
    if (!form.checkValidity()) { event.preventDefault(); form.classList.add('was-validated'); return; }
    const pw=form.querySelector('[name="password"]');
    const confirm=form.querySelector('[name="confirm_password"]');
    if(pw && confirm && pw.value!==confirm.value){event.preventDefault();confirm.setCustomValidity('Passwords do not match');form.classList.add('was-validated');}
  });
  form.querySelectorAll('input').forEach(input => input.addEventListener('input',()=>input.setCustomValidity('')));
});
const search=document.querySelector('#liveSearch');
if(search){
 const results=document.querySelector('#projectResults');let controller;
 const load=async()=>{
  if(controller)controller.abort();controller=new AbortController();
  results.setAttribute('aria-busy','true');
  try{
   const response=await fetch(search.dataset.url+'?q='+encodeURIComponent(search.value),{signal:controller.signal});
   if(!response.ok)throw Error('Could not load projects');
   results.innerHTML=await response.text();
  }catch(err){if(err.name!=='AbortError')results.textContent='Could not load results. Try again.';}
  finally{results.removeAttribute('aria-busy');}
 };
 let timer;search.addEventListener('input',()=>{clearTimeout(timer);timer=setTimeout(load,180);});load();
}
