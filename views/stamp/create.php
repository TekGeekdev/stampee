{{ include('layouts/header.php', {title:'Création timbre'})}}
<main>
<section class="form ombre-permanent">
        <h2>Enregistrer un timbre</h2>
        <form method="post">
            <div>
                <label for="name">Titre</label>
                <input type="text" name="name" id="name"
                placeholder="Saissisez nom du timbre" value="{{stamp.name}}">
                {% if errors.name is defined %}
                <span class="span-erreur"> {{errors.name}}</span>
                {% endif %}
            </div>
            <div>
                <label for="dateRelease">Date d'émission</label>
                <input type="date" name="dateRelease" id="dateRelease"
                placeholder="Saissisez nom du tombre" value="{{stamp.dateRelease}}">
                {% if errors.dateRelease is defined %}
                <span class="span-erreur"> {{errors.dateRelease}}</span>
                {% endif %}
            </div>
            <div>
                <label for="draw">Tirage</label>
                <input type="text" name="draw" id="draw"
                placeholder="Saissisez un tirage" min="0" step="0.01" value="{{stamp.draw}}">
                {% if errors.draw is defined %}
                <span class="span-erreur"> {{errors.draw}}</span>
                {% endif %}
            </div>
            <div>
                <label for="width">Largeur en cm</label>
                <input type="number" name="width" id="width"
                placeholder="Saissisez une valeur" min="0" step="0.01" value="{{stamp.width}}">
                {% if errors.width is defined %}
                <span class="span-erreur"> {{errors.width}}</span>
                {% endif %}
            </div>
            <div>
                <label for="height">Longueur en cm</label>
                <input type="number" name="height" id="height"
                placeholder="Saissisez une valeur" min="0" step="0.01" value="{{stamp.height}}">
                {% if errors.height is defined %}
                <span class="span-erreur"> {{errors.height}}</span>
                {% endif %}
            </div>
            <div>
            <label for="certified">Certifier</label>
                <select name="certified" id="certified">
                    <option value="disabled" {% if stamp.certified ==  null %} selected {% endif %} disabled>Choisir une option</option>
                    <option value="0" {% if stamp.certified ==  "0" %} selected  {% endif %}>non</option>
                    <option value="1" {% if stamp.certified ==  "1" %} selected {% endif %}>oui</option>
                </select>
                {% if errors.certified is defined %}
                <span class="span-erreur"> {{errors.certified}}</span>
                {% endif %}
            </div>
            <div>
                <label for="color_id">Couleur dominante</label>
                <select name="color_id" id="color_id">
                    <option value="disabled" {% if stamp.color_id ==  null %} selected {% endif %} disabled>Choisir une couleur</option>
                    {% for color in colors %}
                        <option value="{{color.id}}" {% if stamp.color_id ==  color.id %} selected {% endif %}>{{color.color}}</option>
                    {% endfor %}
                </select>
                {% if errors.color_id is defined %}
                <span class="span-erreur"> {{errors.color_id}}</span>
                {% endif %}
            </div>
            <div>
                <label for="countryOrigin_id">Pays d'émission</label>
                <select name="countryOrigin_id" id="countryOrigin_id">
                    <option value="disabled" {% if stamp.countryOrigin_id ==  null %} selected {% endif %} disabled>Choisir un pays</option>
                    {% for country in countries %}
                        <option value="{{country.id}}" {% if stamp.countryOrigin_id ==  country.id %} selected {% endif %}>{{country.country}}</option>
                    {% endfor %}
                </select>
                {% if errors.countryOrigin_id is defined %}
                <span class="span-erreur"> {{errors.countryOrigin_id}}</span>
                {% endif %}
            </div><div>
                <label for="conditions_id">État/Qualité</label>
                <select name="conditions_id" id="conditions_id">
                    <option value="disabled" {% if stamp.conditions_id ==  null %} selected {% endif %}  disabled>Choisir un état</option>
                    {% for condition in conditions %}
                        <option value="{{condition.id}}" {% if stamp.conditions_id ==  condition.id %} selected {% endif %}>{{condition.state}}</option>
                    {% endfor %}
                </select>
                {% if errors.conditions_id is defined %}
                <span class="span-erreur"> {{errors.conditions_id}}</span>
                {% endif %}
            </div>
            <div>
                <label for="content">Saissisez une description</label>
                <textarea name="content" id="content" rows="15">{{stamp.content}}</textarea>
                {% if errors.content is defined %}
                <span class="span-erreur"> {{errors.content}}</span>
                {% endif %}
            </div>
            <input type="submit" value="Créer le timbre" class="bouton">
        </form>
    </section>
</main>

{{ include('template/up-page.php')}}
{{ include('layouts/footer.php')}}
