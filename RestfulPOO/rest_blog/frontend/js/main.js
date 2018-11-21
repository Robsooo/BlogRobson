const select = document.querySelector("select")
const main = document.querySelector("main")
const header = document.querySelector("header")
const meu_blog = document.querySelector("span")

const xhr = new XMLHttpRequest()
xhr.open("GET", "http://localhost/RestfulPOO/rest_blog/api/categoria/read.php");
xhr.send();
xhr.onreadystatechange=function() {
    if(this.readyState == 4 && this.status == 200) {
        console.log(xhr.responseText);
        var dados = JSON.parse(xhr.responseText);
        for (i=0; i<dados.length;i++){
            var option = document.createElement("option");
            var txt = document.createTextNode(dados[i].nome);
            option.setAttribute("value", dados[i].id)
            option.appendChild(txt);
            select.appendChild(option);
        }
    }
}

meu_blog.addEventListener('click', () => {
    main.innerHTML = ""
    getPosts()
})

select.addEventListener('change', ev => {
    if(ev.target.selectedIndex == 0) return
    
    getPosts(null, select.value)
    
    main.innerHTML = ""
})

async function getPosts(id,idcategoria){
    let url="http://localhost/RestfulPOO/rest_blog/api/post/read.php"
    if(id!=null){
        url+='?id='+id
    }else if(idcategoria!=null){
        url+='?idcategoria='+idcategoria
    }
    
    let request = await fetch(url)
    let resposta = await request.json()
    
    resposta.forEach(post => {
        const div_post = document.createElement("div")
        div_post.className = "post"
        const div_autor = document.createElement("div")
        const div_data = document.createElement("div")
        div_autor.className = "dados"
        div_data.className = "dados"
        const h1 = document.createElement("h1")
        const p = document.createElement("p")
        
        h1.innerText = post.titulo
        div_autor.innerText = post.autor
        div_data.innerText += post.dt_criacao
        p.innerText=post.texto
        
        div_post.appendChild(h1)
        div_post.appendChild(div_autor)
        div_post.appendChild(div_data)
        div_post.appendChild(p)
        main.appendChild(div_post)
    })
}

async function getCurrentWeather(lat, lon) {
    const div_tempo = document.createElement("div")
    div_tempo.className = "meteorologia"
    header.appendChild(div_tempo)

    let url = "http://api.openweathermap.org/data/2.5/weather?lat="+ lat +"&lon=" + lon +"&units=metric&lang=pt&APPID=c144ba4eaa5f0aebc01c661169701dc7"
    
    let request = await fetch(url)
    let previsao = await request.json()
    console.log(previsao)
    
    var tempo = previsao.weather[0].main
    console.log(tempo)
    
    const clima = document.createElement("div")
    clima.className = "clima"
    const img = document.createElement("img")


    const local = document.createElement("div")
    local.className = "local"

    div_tempo.appendChild(clima)
    div_tempo.appendChild(local)
    
    clima.innerText = previsao.main.temp + "°" 
    local.innerText = previsao.name + ", " + previsao.sys.country
    
    if(tempo == "Clouds") {
        img.src = 'images/weather-icons/002-cloud.png'
        clima.appendChild(img)
    } else if(tempo == "Clear") {
        img.src = 'images/weather-icons/007-cloudy-2.png'
       clima.appendChild(img)
    }

    // console.log(navigator.geolocation)
}

(async () => {
    getPosts()
    getCurrentWeather('-26.9911', '-48.6352')
    // getCurrentWeather('39.03385', '125.75432') 
})()