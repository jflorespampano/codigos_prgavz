//https://es.vuejs.org/v2/guide/forms.html
const { createApp } = Vue

const app = createApp({
  data() {
    return {
      message: 'Cargando datos...!',
      datosCargados: true,
      albums: [],
      post: {
        id: '1',
        userId: '2',
        title: 'mi titulo'
      }
    }
  },
  methods: {
    agregarPost() {
      //https://jsonplaceholder.typicode.com/albums
      const datajson = JSON.stringify(app.post)
      fetch('https://jsonplaceholder.typicode.com/albums', {
        method: 'POST',
        body: datajson,
        headers: {
          'Content-type': 'application/json; charset=UTF-8',
        },
      })
        .then((response) => response.json())
        .then((json) => {
          console.log(json);
          app.datosCargados = true;
          app.ponMensaje(`Dato: ${datajson} Agregado correctamente`);
        });
    },
    ponMensaje(m) {
      app.message = m;
      setTimeout(function () {
        app.message = "";
        app.datosCargados = false;
      }, 3000);
    }
  },
  mounted() {
    fetch('https://jsonplaceholder.typicode.com/albums')
      .then(function (data) {
        if (data.ok) {
          data.json().then(function (datosjson) {
            app.albums = datosjson.slice(1, 10);
            app.datosCargados = true;
            app.ponMensaje("Datos  de la tabla recuperados correctamente");
            console.log("datos cargados");
          });
        } else {
          app.datosCargados = false;
          app.ponMensaje("error en la respuesta del servidor 😢");
        }
      })
      .catch(function (error) {
        app.datosCargados = false;
        app.ponMensaje("error en la red 😢");
      });
  }
}).mount('#app')

