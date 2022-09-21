let paso=1;const pasoInicial=1,pasoFinal=3,cita={id:"",nombre:"",fecha:"",hora:"",servicios:[]};function iniciarApp(){mostrarSeccion(),tabs(),botonesPaginador(),paginaSiguiente(),paginaAnterior(),consultarAPI(),idCliente(),nombreCliente(),fechaCita(),horaCita()}function mostrarSeccion(){const e=document.querySelector(".mostrar");e&&e.classList.remove("mostrar");const t="#paso-"+paso;document.querySelector(t).classList.add("mostrar");const a=document.querySelector(".actual");a&&a.classList.remove("actual");document.querySelector(`[data-paso="${paso}"]`).classList.add("actual")}function tabs(){document.querySelectorAll(".tabs button").forEach(e=>{e.addEventListener("click",(function(e){paso=parseInt(e.target.dataset.paso),mostrarSeccion(),botonesPaginador()}))})}function botonesPaginador(){const e=document.querySelector("#anterior"),t=document.querySelector("#siguiente");switch(paso){case 1:eliminarAlertas(!0),e.classList.add("no-visible"),t.classList.remove("no-visible");break;case 2:eliminarAlertas(!0),e.classList.remove("no-visible"),t.classList.remove("no-visible");break;case 3:eliminarAlertas(!0),t.classList.add("no-visible"),e.classList.remove("no-visible"),mostrarResumen()}mostrarSeccion()}function paginaAnterior(){document.querySelector("#anterior").addEventListener("click",(function(){paso<=1||(paso--,botonesPaginador())}))}function paginaSiguiente(){document.querySelector("#siguiente").addEventListener("click",(function(){paso>=3||(paso++,botonesPaginador())}))}async function consultarAPI(){try{const e="https://obscure-lowlands-28552.herokuapp.com/api/servicios",t=await fetch(e);mostrarServiciosAPI(await t.json())}catch(e){console.log(e)}}function mostrarServiciosAPI(e){e.forEach(e=>{const{id:t,nombre:a,precio:o}=e,r=document.createElement("P");r.classList.add("nombre-servicio"),r.textContent=a;const n=document.createElement("P");n.classList.add("precio-servicio"),n.textContent=o+" €";const i=document.createElement("DIV");i.classList.add("servicio"),i.dataset.idServicio=t,i.onclick=function(){seleccionarServicio(e)},i.appendChild(r),i.appendChild(n),document.querySelector("#servicios").appendChild(i)})}function seleccionarServicio(e){const{id:t}=e,{servicios:a}=cita,o=document.querySelector(`[data-id-servicio="${t}"]`);if(a.some(t=>t.id===e.id))cita.servicios=a.filter(e=>e.id!==t),o.classList.remove("seleccionado"),console.log("Ya esta agregado");else{if(!(cita.servicios.length<=5))return;cita.servicios=[...a,e],o.classList.add("seleccionado"),console.log("Nuevo")}}function nombreCliente(){cita.nombre=document.querySelector("#nombre").placeholder}function idCliente(){cita.id=document.querySelector("#id").value}function fechaCita(){const e=document.querySelector("#fecha");e.addEventListener("input",(function(t){const a=new Date(t.target.value).getUTCDay(),o=e.value.toString();!1===validarFechaEscrita(o,e)&&(o.value="",mostrarAlerta("Introduce una fecha valida","error",".alertas-contenedor")),[6,0].includes(a)?(t.target.value="",mostrarAlerta("Sabados y Domingos permanecemos cerrados","error",".alertas-contenedor")):cita.fecha=t.target.value}))}function validarFechaEscrita(e,t){let a=new Date;const o=String(a.getDate()).padStart(2,"0"),r=String(a.getMonth()+1).padStart(2,"0"),n=a.getFullYear();a=n+"-"+r+"-"+o;const i=new Date(a),[c,s,l]=e.split("-"),d=`${c}-${s}-${l}`,u=new Date(d);u<i&&(t.value="",mostrarAlerta("No puedes introducir una fecha pasada.","error",".alertas-contenedor"));const m=u.getTime();return"number"==typeof m&&!Number.isNaN(m)&&u.toISOString().startsWith(d)}function horaCita(){document.querySelector("#hora").addEventListener("input",(function(e){const t=e.target.value.split(":")[0];t<10||t>20?(mostrarAlerta("El horario es de 10:00 a 14:00 y de 16:00 a 20:00","error",".alertas-contenedor"),e.target.value=""):t>=14&&t<16?(mostrarAlerta("De 14:00 a 16:00 estamos de descanso","error",".alertas-contenedor"),e.target.value=""):cita.hora=e.target.value}))}function mostrarAlerta(e,t,a,o=!0){if(document.querySelector(".alerta"))return;const r=document.querySelector(a),n=document.createElement("DIV");n.textContent=e,n.classList.add("alerta"),n.classList.add(t),r.appendChild(n),!0===o&&eliminarAlertas()}function mostrarResumen(){const e=document.querySelector(".alertas-resumen"),t=document.querySelector(".contenedor-servicios"),a=document.querySelector("#paso-3"),o=document.querySelector(".contenedor-datos");for(;e.firstChild;)e.removeChild(e.firstChild);for(;t.firstChild;)t.removeChild(t.firstChild);for(;o.firstChild;)o.removeChild(o.firstChild);if(Object.values(cita).includes("")||0===cita.servicios.length)return void mostrarAlerta("Revisa los servicios y la fecha/hora de la cita","error",".alertas-resumen",!1);const{nombre:r,fecha:n,hora:i,servicios:c}=cita,s=document.createElement("H3");s.textContent="Servicios solicitados",t.appendChild(s),c.forEach(e=>{const{id:a,precio:o,nombre:r}=e,n=document.createElement("DIV");n.classList.add("contenedor-servicio");const i=document.createElement("P");i.textContent=r;const c=document.createElement("P");c.innerHTML=`<span>Precio: </span> ${o}€`,n.appendChild(i),n.appendChild(c),t.appendChild(n)});const l=document.createElement("H3");l.textContent="Resumen de la cita",o.appendChild(l);const d=document.createElement("P");d.innerHTML="<span>Nombre: </span>"+r;const u=new Date(n),m=u.getMonth(),p=u.getDate(),h=u.getFullYear(),v=new Date(Date.UTC(h,m,p)).toLocaleDateString("es-ES",{weekday:"long",year:"numeric",month:"long",day:"numeric"}),f=document.createElement("P");f.innerHTML="<span>Fecha: </span>"+v;const S=document.createElement("P");S.innerHTML=`<span>Hora: </span>${i}h`;document.createElement("P").innerHTML="<span>Servicios:</span>"+c;const g=document.createElement("BUTTON");g.classList.add("boton"),g.textContent="Reservar Cita",g.onclick=reservarCita,o.appendChild(d),o.appendChild(f),o.appendChild(S),o.appendChild(g),a.appendChild(o)}async function reservarCita(){const{id:e,nombre:t,fecha:a,hora:o,servicios:r}=cita,n=r.map(e=>e.id),i=new FormData;i.append("usuarioid",e),i.append("fecha",a),i.append("hora",o),i.append("servicios",n);try{const e="https://obscure-lowlands-28552.herokuapp.com/api/citas",t=await fetch(e,{method:"POST",body:i}),a=await t.json();a.resultado?Swal.fire({icon:"success",title:"Cita Creada",text:"Tu cita ha sido creada correctamente"}).then(()=>{window.location.reload()}):"3citasmax"==a&&Swal.fire({icon:"error",title:"Error",text:"¡El maximo de citas por cliente son tres! Anula alguna de tus citas en el apartado 'Mis citas'."})}catch(e){Swal.fire({icon:"error",title:"Error",text:"Hubo un error al guardar la cita"})}}document.addEventListener("DOMContentLoaded",(function(){iniciarApp()}));