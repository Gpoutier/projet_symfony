const villeSelect = document.getElementById('sortie_form_ville');
const lieuSelect = document.getElementById('sortie_form_lieu');
const placement = document.getElementById('inputLieu');

villeSelect.addEventListener('change', () => {
    const villeId = villeSelect.value;
    console.log(villeSelect);
    console.log(villeId);
    fetch(`/projet_symfony/public/lieux/${villeId}`)
        .then(response => response.json())
        .then(data => {
            console.log(data);
            lieuSelect.innerHTML = '';

            data.forEach(lieu => {
                const option = document.createElement('option');
                option.value = lieu.id;
                option.textContent = lieu.nom;
                lieuSelect.appendChild(option);
            });

            lieuSelect.addEventListener('change', () => {
                const selectedLieu = data.find(lieu => lieu.id.toString() === lieuSelect.value);
                console.log(selectedLieu.rue);
                inputLieu.innerHTML = "";
                if (selectedLieu) {
                    createInputLabel("Rue :");
                    createInput(data, selectedLieu.rue);
                    createInputLabel("Code postal :");
                    createInput(data, selectedLieu.codePostal);
                }
            });
        });
});

function createInputLabel(text) {
    const label = document.createElement("label");
    label.className = "form-label";
    label.textContent = text;
    inputLieu.appendChild(label);
}

function createInput(data, donneesInput){
    var inputElement = document.createElement("input");
    inputElement.type = "text";
    inputElement.id = donneesInput;
    inputElement.className = "form-control";
    inputElement.value = donneesInput;
    inputElement.disabled = true;
    placement.appendChild(inputElement);
}