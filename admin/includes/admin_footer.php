  </div><!-- /admin-content -->
</div><!-- /admin-main -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleSidebar(){document.getElementById('adminSidebar').classList.toggle('open')}
function confirmDelete(url,msg){if(confirm(msg||'Delete this item?')){window.location=url}}
function showToast(msg,type){
  const t=document.createElement('div');
  t.className='position-fixed bottom-0 end-0 p-3';t.style.zIndex=9999;
  t.innerHTML=`<div class="toast show align-items-center text-white bg-${type==='success'?'success':'danger'} border-0"><div class="d-flex"><div class="toast-body">${msg}</div><button type="button" class="btn-close btn-close-white me-2 m-auto" onclick="this.closest('.position-fixed').remove()"></button></div></div>`;
  document.body.appendChild(t);setTimeout(()=>t.remove(),4000);
}
// Flash message from URL
const urlParams=new URLSearchParams(window.location.search);
if(urlParams.get('success')) showToast(decodeURIComponent(urlParams.get('success')),'success');
if(urlParams.get('error')) showToast(decodeURIComponent(urlParams.get('error')),'danger');
</script>
<?php if(isset($extraScripts)) echo $extraScripts; ?>
</body>
</html>
