!function(){let e=[],t=[];const a=""+location.origin,n=new URLSearchParams(window.location.search),o=Object.fromEntries(n.entries());!async function(){const t=a+"/api/tareas?url="+o.url;try{const a=await fetch(t),n=await a.json();e=n.tareas,c()}catch(e){}}();document.querySelector("#agregar-tarea").addEventListener("click",(function(){i()}));function r(a){const n=a.target.value;t=""!==n?e.filter(e=>e.estado===n):[],c()}function c(){!function(){const e=document.querySelector("#listado-tareas");for(;e.firstChild;)e.removeChild(e.firstChild)}(),function(){const t=e.filter(e=>"0"===e.estado);document.querySelector("#pendientes").disabled=0===t.length;const a=e.filter(e=>"1"===e.estado);document.querySelector("#completadas").disabled=0===a.length}();const n=t.length?t:e,r=document.querySelector("#listado-tareas");if(0===n.length){const e=document.createElement("LI");return e.textContent="No Hay Tareas Disponibles",e.classList.add("sin-tareas"),void r.appendChild(e)}const s={0:"Pendiente",1:"Completa"};n.forEach(t=>{const n=document.createElement("LI");n.dataset.tareaId=t.id,n.classList.add("tarea");const l=document.createElement("P");l.textContent=t.nombre,l.ondblclick=function(){i(!0,{...t})};const u=document.createElement("DIV");u.classList.add("opciones");const m=document.createElement("BUTTON");m.classList.add("estado-tarea"),m.classList.add(""+s[t.estado].toLowerCase()),m.textContent=s[t.estado],m.dataset.estadoTarea=t.estado,m.ondblclick=function(){d({...t},!0)};const p=document.createElement("BUTTON");p.classList.add("eliminar-tarea"),p.textContent="Eliminar",p.dataset.idTarea=t.id,p.ondblclick=function(){!function(t){Swal.fire({title:"¿Está seguro que desea eliminar esta tarea?<br><br>"+t.nombre,showCancelButton:!0,confirmButtonText:"Si, eliminar",cancelButtonText:"Cancelar"}).then(n=>{n.isConfirmed&&async function(t){const{estado:n,id:r,nombre:i}=t,s=new FormData;s.append("id",r),s.append("nombre",i),s.append("estado",n),s.append("proyectoUrl",o.url);try{const n=a+"/api/tarea/eliminar",o=await fetch(n,{method:"POST",body:s}),r=await o.json();"exito"===r.tipo&&(e=e.filter(e=>e.id!==t.id),c(),Swal.fire(r.mensaje,"","success"))}catch(e){console.log(e)}}(t)})}({...t})},u.appendChild(m),u.appendChild(p),n.appendChild(l),n.appendChild(u),r.appendChild(n)})}function i(t=!1,n={}){const r=document.createElement("DIV");r.classList.add("modal"),r.innerHTML=`\n        <form class="formulario nueva-tarea">\n            <legend>${t?"Editar Tarea":"Añade una nueva Tarea"}</legend>\n            <div class="campo">\n                <label for="tarea">Tarea</label>\n                <input \n                    type="text"\n                    name="tarea"\n                    id="tarea"\n                    placeholder="${n.nombre?n.nombre:"Añade una tarea al Proyecto Actual"}"\n                    value="${n.nombre?n.nombre:""}"\n                />\n            </div>\n            <div class="opciones">\n                <button type="button" class="cerrar-modal">Cancelar</button>\n                <input \n                    type="submit"\n                    class="submit-nueva-tarea"\n                    value="${t?"Guardar Cambios":"Añadir Tarea"}"\n                 />\n            </div>\n        </form>\n        `,setTimeout(()=>{document.querySelector(".formulario").classList.add("animar")},10),r.addEventListener("click",(function(i){if(i.preventDefault(),i.target.classList.contains("cerrar-modal")||i.target.classList.contains("modal")){document.querySelector(".formulario").classList.add("cerrar"),setTimeout(()=>{r.remove()},400)}if(i.target.classList.contains("submit-nueva-tarea")){const r=document.querySelector("#tarea").value.trim();if(""===r){return void s("El nombre de la Tarea es Obligatorio","error",document.querySelector(".formulario legend"))}if(t)return n.nombre=r,void d(n);!async function(t){const n=new FormData;n.append("nombre",t),n.append("proyectoUrl",o.url);try{const o=a+"/api/tarea",r=await fetch(o,{method:"POST",body:n}),i=await r.json(),d=document.querySelector(".formulario legend");if(s(i.mensaje,i.tipo,d),"exito"===i.tipo){const a=document.querySelector(".modal");setTimeout(()=>{a.remove()},2e3);const n={id:String(i.id),nombre:t,estado:"0",proyectoId:i.proyectoId};e=[...e,n],c()}}catch(e){console.log(e)}}(r)}})),document.querySelector(".dashboard").appendChild(r)}function s(e,t,a){const n=document.querySelector(".alerta");n&&n.remove();const o=document.createElement("DIV");o.classList.add("alerta",t),o.textContent=e,a.parentElement.insertBefore(o,a.nextElementSibling),setTimeout(()=>{o.remove()},5e3)}async function d(t,n=!1){if(n){const e="1"===t.estado?"0":"1";t.estado=e}const{estado:r,id:i,nombre:s}=t,d=new FormData;d.append("id",i),d.append("nombre",s),d.append("estado",r),d.append("proyectoUrl",o.url);try{const t=a+"/api/tarea/actualizar",n=await fetch(t,{method:"POST",body:d}),o=await n.json();if("exito"===o.tipo){const t=document.querySelector(".modal");t&&t.remove(),Swal.fire(o.mensaje,"","success"),e=e.map(e=>(e.id===i&&(e.estado=r,e.nombre=s),e)),c()}}catch(e){console.log(e)}}document.querySelectorAll("#filtros input[type='radio']").forEach(e=>{e.addEventListener("input",r)})}();