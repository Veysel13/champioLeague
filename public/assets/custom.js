let divElement = $(".scoreCard");
let divHeight = divElement[0].offsetHeight;

let cardDivs = $(".card");
for (let i = 0; i < cardDivs.length; i++) {
    cardDivs[i].style.height = divHeight+"px";
}

$(document).ready(function() {
    $("html, body").animate({ scrollTop: $(document).height() }, "slow");
});
