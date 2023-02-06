!function(){let e=[];const t=""+location.origin,a=new URLSearchParams(window.location.search),n=Object.fromEntries(a.entries());!async function(){const a=t+"/api/tareas?url="+n.url;try{const t=await fetch(a),n=await t.json();e=n.tareas,o()}catch(e){}}();function o(){!function(){const e=document.querySelector("#listado-tareas");for(;e.firstChild;)e.removeChild(e.firstChild)}();const a=document.querySelector("#listado-tareas");if(0===e.length){const e=document.createElement("LI");return e.textContent="No Hay Tareas Disponibles",e.classList.add("sin-tareas"),void a.appendChild(e)}const i={0:"Pendiente",1:"Completa"};e.forEach(s=>{const d=document.createElement("LI");d.dataset.tareaId=s.id,d.classList.add("tarea");const l=document.createElement("P");l.textContent=s.nombre,l.ondblclick=function(){r(!0,s)};const m=document.createElement("DIV");m.classList.add("opciones");const u=document.createElement("BUTTON");u.classList.add("estado-tarea"),u.classList.add(""+i[s.estado].toLowerCase()),u.textContent=i[s.estado],u.dataset.estadoTarea=s.estado,u.ondblclick=function(){!async function(a){const r="1"===a.estado?"0":"1";a.estado=r;const{estado:i,id:s,nombre:d}=a,l=new FormData;l.append("id",s),l.append("nombre",d),l.append("estado",i),l.append("proyectoUrl",n.url);try{const a=t+"/api/tarea/actualizar",n=await fetch(a,{method:"POST",body:l}),r=await n.json();"exito"===r.tipo&&(c(r.mensaje,r.tipo,document.querySelector(".contenedor-nueva-tarea")),e=e.map(e=>(e.id===s&&(e.estado=i),e)),o())}catch(e){console.log(e)}}({...s})};const p=document.createElement("BUTTON");p.classList.add("eliminar-tarea"),p.textContent="Eliminar",p.dataset.idTarea=s.id,p.ondblclick=function(){!function(a){Swal.fire({title:"¿Está seguro que desea eliminar esta tarea?<br><br>"+a.nombre,showCancelButton:!0,confirmButtonText:"Si, eliminar",cancelButtonText:"Cancelar"}).then(r=>{r.isConfirmed&&async function(a){const{estado:r,id:c,nombre:i}=a,s=new FormData;s.append("id",c),s.append("nombre",i),s.append("estado",r),s.append("proyectoUrl",n.url);try{const n=t+"/api/tarea/eliminar",r=await fetch(n,{method:"POST",body:s}),c=await r.json();"exito"===c.tipo&&(e=e.filter(e=>e.id!==a.id),o(),Swal.fire(c.mensaje,"","success"))}catch(e){console.log(e)}}(a)})}({...s})},m.appendChild(u),m.appendChild(p),d.appendChild(l),d.appendChild(m),a.appendChild(d)})}function r(a=!1,r={}){const i=document.createElement("DIV");i.classList.add("modal"),i.innerHTML=`\n        <form class="formulario nueva-tarea">\n            <legend>${a?"Editar Tarea":"Añade una nueva Tarea"}</legend>\n            <div class="campo">\n                <label for="tarea">Tarea</label>\n                <input \n                    type="text"\n                    name="tarea"\n                    id="tarea"\n                    placeholder="${r.nombre?r.nombre:"Añade una tarea al Proyecto Actual"}"\n                    value="${r.nombre?r.nombre:""}"\n                />\n            </div>\n            <div class="opciones">\n                <button type="button" class="cerrar-modal">Cancelar</button>\n                <input \n                    type="submit"\n                    class="submit-nueva-tarea"\n                    value="${a?"Guardar Cambios":"Añadir Tarea"}"\n                 />\n            </div>\n        </form>\n        `,setTimeout(()=>{document.querySelector(".formulario").classList.add("animar")},10),i.addEventListener("click",(function(a){if(a.preventDefault(),a.target.classList.contains("cerrar-modal")||a.target.classList.contains("modal")){document.querySelector(".formulario").classList.add("cerrar"),setTimeout(()=>{i.remove()},400)}a.target.classList.contains("submit-nueva-tarea")&&function(){const a=document.querySelector("#tarea").value.trim();if(""===a){return void c("El nombre de la Tarea es Obligatorio","error",document.querySelector(".formulario legend"))}!async function(a){const r=new FormData;r.append("nombre",a),r.append("proyectoUrl",n.url);try{const n=t+"/api/tarea",i=await fetch(n,{method:"POST",body:r}),s=await i.json(),d=document.querySelector(".formulario legend");if(c(s.mensaje,s.tipo,d),"exito"===s.tipo){const t=document.querySelector(".modal");setTimeout(()=>{t.remove()},2e3);const n={id:String(s.id),nombre:a,estado:"0",proyectoId:s.proyectoId};e=[...e,n],o()}}catch(e){console.log(e)}}(a)}()})),document.querySelector(".dashboard").appendChild(i)}function c(e,t,a){const n=document.querySelector(".alerta");n&&n.remove();const o=document.createElement("DIV");o.classList.add("alerta",t),o.textContent=e,a.parentElement.insertBefore(o,a.nextElementSibling),setTimeout(()=>{o.remove()},5e3)}document.querySelector("#agregar-tarea").addEventListener("click",(function(){r()}))}();