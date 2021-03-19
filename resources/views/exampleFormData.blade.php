<h1>
    IMAGE UPLOAD
</h1>

<input type="file" id="photo" name="photo" accept="image/png, image/jpeg">

<button class="btn-active">
    UPLOAD
</button>

<script>
    let btnsActive = document.querySelector('.btn-active');
    var photo = document.querySelector('#photo')

    btnsActive.onclick = () => {

        const  data = new FormData()
        data.append('photo', photo.files[0]);
        data.append('country_id', 1)
        data.append('name', 'Team Teste')

        fetch('http://localhost:8000/api/team/updateWithImage/1', {
            method: "post",
            body: data,
            headers: {
            },
        })
        .then( data => data.json())
        .then((response) => {
            console.log(response);
        });
    };

</script>
