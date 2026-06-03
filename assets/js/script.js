const days = document.querySelectorAll('.calendar-day');

const dateInput = document.getElementById('selectedDate');

days.forEach(day => {

    day.addEventListener('click', () => {

        if(day.classList.contains('closed')){
            return;
        }

        days.forEach(d => {
            d.classList.remove('selected');
        });

        day.classList.add('selected');

        dateInput.value = day.dataset.date;

    });

});

const modal = document.getElementById("menuModal");

const openBtn = document.getElementById("openMenuModal");

const closeBtn = document.getElementById("closeModal");

const selectedCount =
document.getElementById("selectedCount");

const selectedDishes =
document.getElementById("selectedDishes");

const selectedTotal =
document.getElementById("selectedTotal");

if(openBtn){

    openBtn.onclick = () => {

        modal.style.display = "block";

    };

}

if(closeBtn){

    closeBtn.onclick = () => {

        modal.style.display = "none";

    };

}

window.addEventListener("click", function(e){

    if(e.target === modal){

        modal.style.display = "none";

    }

});

function updateSelectedDishes(){

    const checked =
    document.querySelectorAll(
    'input[name="menu_items[]"]:checked'
    );

    if(checked.length > 0){

        selectedCount.innerHTML =
        checked.length + " dishes selected";

    }else{

        selectedCount.innerHTML =
        "No dishes selected";

    }

    let dishesHtml = "";

    let total = 0;

    checked.forEach(item => {

        const text =
        item.parentElement.innerText.trim();

        dishesHtml += `
        <div class="selected-dish">

            <span>${text}</span>

            <button
            type="button"
            class="remove-dish"
            data-name="${text}">

                ✕

            </button>

        </div>
        `;

        const match =
        text.match(/€([\d.]+)/);

        if(match){

            total +=
            parseFloat(match[1]);

        }

    });

    selectedDishes.innerHTML =
    dishesHtml;

    if(total > 0){

        selectedTotal.innerHTML =
        "<strong>Pre-Order Total: €" +
        total.toFixed(2) +
        "</strong>";

    }else{

        selectedTotal.innerHTML = "";

    }

}

document
.querySelectorAll(
'input[name="menu_items[]"]'
)
.forEach(checkbox => {

    checkbox.addEventListener(
    "change",
    updateSelectedDishes
    );

});

/* REMOVE DISH */

document.addEventListener("click", function(e){

    if(e.target.classList.contains("remove-dish")){

        const dishName =
        e.target.dataset.name;

        document
        .querySelectorAll(
        'input[name="menu_items[]"]'
        )
        .forEach(checkbox => {

            const itemText =
            checkbox.parentElement.innerText.trim();

            if(itemText === dishName){

                checkbox.checked = false;

            }

        });

        updateSelectedDishes();

    }

});