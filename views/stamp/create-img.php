{{ include('layouts/header.php', {title:'Création timbre'})}}

<main>
    <section class="form ombre-permanent">
        <h2>Enregistrer un timbre</h2>
        <form method="post" enctype="multipart/form-data">           
            <div>
                <label for="file">Choissisez une image principale</label>
                <input type="file" name="file" id="file">
                {% if errors.file is defined %}
                <span class="span-erreur"> {{errors.file}}</span>
                {% endif %}
            </div>
            <div>
                <label for="description">Description de l'image principale</label>
                <input type="text" name="description" id="description"
                placeholder="Saissisez une description" value="{{description.description}}">
                {% if errors.description is defined %}
                <span class="span-erreur"> {{errors.description}}</span>
                {% endif %}
            </div>
            <div>
                <label for="secondFile">Choissisez une seconde image</label>
                <input type="file" name="secondFile" id="secondFile">
                {% if errors.secondFile is defined %}
                <span class="span-erreur"> {{errors.secondFile}}</span>
                {% endif %}
            </div>
            <div>
                <label for="secondDescription">Description de la seconde image</label>
                <input type="text" name="secondDescription" id="secondDescription"
                placeholder="Saissisez une description" value="{{description.secondDescription}}">
                {% if errors.secondDescription is defined %}
                <span class="span-erreur"> {{errors.secondDescription}}</span>
                {% endif %}
            </div>
            <div>
                <label for="thirdFile">Choissisez une troisième image</label>
                <input type="file" name="thirdFile" id="thirdFile">
                {% if errors.file is defined %}
                <span class="span-erreur"> {{errors.thirdFile}}</span>
                {% endif %}
            </div>
            <div>
                <label for="thirdDescription">Description de la troisième image</label>
                <input type="text" name="thirdDescription" id="thirdDescription"
                placeholder="Saissisez une description" value="{{description.thirdDescription}}">
                {% if errors.thirdDescription is defined %}
                <span class="span-erreur"> {{errors.thirdDescription}}</span>
                {% endif %}
            </div>  
            <input type="submit" value="Créer les images" class="bouton">
        </form>
    </section>
</main>

{{ include('template/up-page.php')}}
{{ include('layouts/footer.php')}}
