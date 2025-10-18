function switchTab(i) {
    const tabs = document.querySelectorAll(".tab");
    const contents = document.querySelectorAll(".tab-content");
    
    tabs.forEach((tab, index) => {
        if (index === i) {
            tab.classList.add("active");
            contents[index].classList.add("active");
        } else {
            tab.classList.remove("active");
            contents[index].classList.remove("active");
        }
    });

}

function toggleEdit() {
    const editSection = document.getElementById('editAccountSection');
    if (editSection.style.display === 'none') {
        editSection.style.display = 'block';
    } else {
        editSection.style.display = 'none';
    }
}