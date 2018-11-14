const select = document.querySelector("select")
const main = document.querySelector("main")
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
    });
}

(async () => {
    getPosts()
})()