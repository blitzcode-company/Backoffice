const videosSeleccionados = [];

function verificarVideos() {
    const selectedVideosContainer = document.getElementById('selectedVideos');
    if (videosSeleccionados.length === 0) {
        mostrarMensajeNoVideos();
    } else {
        ocultarMensajeNoVideos();
    }
}

function buscarVideos() {
    const query = document.getElementById('search').value;
    const resultsContainer = document.getElementById('searchResults');
    
    if (query.length < 1) {
        resultsContainer.innerHTML = '';
        return;
    }

    fetch(`/playlists/buscar/video?q=${query}`)
        .then(response => response.json())
        .then(data => {
            resultsContainer.innerHTML = '';
            
            if (data.length === 0) {
                const li = document.createElement('li');
                li.className = 'list-group-item text-muted';
                li.textContent = 'No hay videos aún';
                resultsContainer.appendChild(li);
                return;
            }

            data.forEach(video => {
                const li = document.createElement('li');
                li.className = 'list-group-item d-flex justify-content-between align-items-center';
                const videoTitle = document.createElement('span');
                videoTitle.textContent = video.titulo;
                videoTitle.style.cursor = 'pointer';
                videoTitle.onclick = () => seleccionarVideo(video);
                const viewBtn = document.createElement('button');
                viewBtn.className = 'button-info btn-sm';
                viewBtn.innerHTML = '<i class="fas fa-info-circle"></i>';
                viewBtn.style.marginLeft = '10px';
                viewBtn.onclick = (e) => {
                    e.stopPropagation();
                    window.open(`/video/${video.id}/detalle`, '_blank');
                };
                li.appendChild(videoTitle);
                li.appendChild(viewBtn);

                resultsContainer.appendChild(li);
            });
        });
}

function seleccionarVideo(video) {
    if (!videosSeleccionados.some(v => v.id === video.id)) {
        videosSeleccionados.push(video);
        agregarVideoALaLista(video);
        actualizarVideosInput();
    }
    document.getElementById('search').value = '';
    document.getElementById('searchResults').innerHTML = '';
    verificarVideos();
}

function agregarVideoALaLista(video) {
    const li = document.createElement('li');
    li.className = 'playlist-list-item video-list';
    li.textContent = video.titulo;
    const closeBtn = document.createElement('button');
    closeBtn.className = 'btn btn-close btn-sm';
    closeBtn.onclick = () => eliminarVideo(video, li);

    li.appendChild(closeBtn);
    document.getElementById('selectedVideos').appendChild(li);
    
    ocultarMensajeNoVideos();
}

function eliminarVideo(video, li) {
    const index = videosSeleccionados.findIndex(v => v.id === video.id);
    if (index > -1) {
        videosSeleccionados.splice(index, 1);
        document.getElementById('selectedVideos').removeChild(li);
        actualizarVideosInput();
        
        verificarVideos();
    }
}

function actualizarVideosInput() {
    const videoIds = videosSeleccionados.map(video => video.id).join(',');
    document.getElementById('videos').value = videoIds;
}

function mostrarMensajeNoVideos() {
    const noVideosMessage = document.createElement('li');
    noVideosMessage.id = 'noVideosMessage';
    noVideosMessage.className = 'list-group-item text-muted';
    noVideosMessage.textContent = 'No hay videos aún';
    document.getElementById('selectedVideos').appendChild(noVideosMessage);
}

function ocultarMensajeNoVideos() {
    const noVideosMessage = document.getElementById('noVideosMessage');
    if (noVideosMessage) {
        noVideosMessage.remove();
    }
}

document.addEventListener('DOMContentLoaded', verificarVideos);
