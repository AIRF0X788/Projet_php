function filterTopics() {
    var category = document.getElementById("category-filter").value;
    var topicItems = document.getElementsByClassName("topic-item");

    for (var i = 0; i < topicItems.length; i++) {
        var topicCategory = topicItems[i].querySelector(".category").textContent;

        if (category === "" || topicCategory === category) {
            topicItems[i].style.display = "block";
        } else {
            topicItems[i].style.display = "none";
        }
    }
}
