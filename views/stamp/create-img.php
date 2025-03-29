{{ include('layouts/header.php', {title:'Création timbre'})}}

<main>
    <section class="form ombre-permanent">
        <h2>Enregistrer un timbre</h2>
        <form method="post" enctype="multipart/form-data">      
            {{insert.id}}      
            <div>
                <label for="file">Choissisez une image principale</label>
                <input type="file" name="file" id="file">
                {% if errors.file is defined %}
                <span class="span-erreur"> {{errors.file}}</span>
                {% endif %}
            </div>
            <div>
                <label for="file2">Choissisez une deuxième image</label>
                <input type="file" name="file2" id="file2">
            </div>
            <!-- <div>
                <label for="file">Choissisez une troisième image</label>
                <input type="file" name="file" id="file">
            </div>
            <div>
                <label for="file">Choissisez une quatirème image</label>
                <input type="file" name="file" id="file">
            </div> -->
            <input type="hidden" name="stamp_id" value="{{insert}}">
            <input type="submit" value="Créer les images" class="bouton">
        </form>
    </section>
</main>

{{ include('template/up-page.php')}}
{{ include('layouts/footer.php')}}
