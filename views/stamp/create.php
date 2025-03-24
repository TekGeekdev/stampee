{{ include('layouts/header.php', {title:'Création compte'})}}
<main>
<section class="form ombre-permanent">
        <h2>Enregistrer un timbre</h2>
        <form method="post">
            <div>
                <label for="name">Titre</label>
                <input type="text" name="name" id="name"
                placeholder="Saissisez nom du tombre" value="">
                {% if errors.name is defined %}
                <span class="span-erreur"> {{errors.name}}</span>
                {% endif %}
            </div>
            <div>
                <label for="dateRelease">Date d'émission</label>
                <input type="date" name="dateRelease" id="dateRelease"
                placeholder="Saissisez nom du tombre" value="">
                {% if errors.name is defined %}
                <span class="span-erreur"> {{errors.name}}</span>
                {% endif %}
            </div>
            <div>
                <label for="draw">Tirage</label>
                <input type="text" name="draw" id="draw"
                placeholder="Saissisez un tirage" value="">
                {% if errors.name is defined %}
                <span class="span-erreur"> {{errors.name}}</span>
                {% endif %}
            </div>
            <div>
                <label for="width">Largeur en cm</label>
                <input type="number" name="width" id="width"
                placeholder="Saissisez une valeur" value="">
                {% if errors.name is defined %}
                <span class="span-erreur"> {{errors.name}}</span>
                {% endif %}
            </div>
            <div>
                <label for="height">Longueur en cm</label>
                <input type="number" name="height" id="height"
                placeholder="Saissisez une valeur" value="">
                {% if errors.name is defined %}
                <span class="span-erreur"> {{errors.name}}</span>
                {% endif %}
            </div>
            <div>
            <label for="certified">Certifier</label>
                <select name="certified" id="certified">
                    <option value="disabled" selected disabled>Choisir une option</option>
                    <option value="0">non</option>
                    <option value="1">oui</option>  
                </select>
            </div>
            <div>
                <label for="color_id">Couleur dominante</label>
                <select name="color_id" id="color_id">
                    <option value="disabled"selected disabled>Choisir une couleur</option>
                    {% for color in colors %}
                        <option value="{{color.id}}">{{color.color}}</option>
                    {% endfor %}
                </select>
            </div>
            <div>
                <label for="countryOrigin_id">Pays d'émission</label>
                <select name="countryOrigin_id" id="countryOrigin_id">
                    <option value="disabled" selected disabled>Choisir un pays</option>
                    {% for country in countries %}
                        <option value="{{country.id}}">{{country.country}}</option>
                    {% endfor %}
                </select>
            </div><div>
                <label for="conditions_id">État/Qualité</label>
                <select name="conditions_id" id="conditions_id">
                    <option value="disabled" selected disabled>Choisir un état</option>
                    {% for condition in conditions %}
                        <option value="{{condition.id}}">{{condition.state}}</option>
                    {% endfor %}
                </select>
            </div>
            <div>
                <label for="file">Choissisez une image</label>
                <input type="file" name="file" id="file">
            </div>
            <div>
                <label for="content">Saissisez une description</label>
                <textarea name="content" id="content" rows="15"></textarea>
                {% if errors.contenu is defined %}
                <span class="span-erreur"> {{errors.contenu}}</span>
                {% endif %}
            </div>
            <input type="submit" value="Créer le timbre" class="bouton">
        </form>
    </section>
</main>


{{ include('layouts/footer.php')}}
