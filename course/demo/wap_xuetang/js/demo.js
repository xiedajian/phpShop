// ----------------------------------------------//
//                                               //
//                唐明明☀20150724                //
//                                               //
// ----------------------------------------------//


//Regional开始
$(document).ready(function(){
    $(".Regional").click(function(){
        if ($('.grade-eject').hasClass('grade-w-roll')) {
            $('.grade-eject').removeClass('grade-w-roll');
        } else {
            $('.grade-eject').addClass('grade-w-roll');
        }
    });
});

$(document).ready(function(){
    $(".grade-w>li").click(function(){
        $(".grade-t")
            .css("left","33.48%")
    });
});

$(document).ready(function(){
    $(".grade-t>li").click(function(){
        $(".grade-s")
            .css("left","66.96%")
    });
});

//Brand开始

$(document).ready(function(){
    $(".Brand").click(function(){
        if ($('.Brand-eject').hasClass('grade-w-roll')) {
            $('.Brand-eject').removeClass('grade-w-roll');
        } else {
            $('.Brand-eject').addClass('grade-w-roll');
        }
    });
});



//Sort开始

$(document).ready(function(){
    $(".Sort").click(function(){
        if ($('.Sort-eject').hasClass('grade-w-roll')) {
            $('.Sort-eject').removeClass('grade-w-roll');
        } else {
            $('.Sort-eject').addClass('grade-w-roll');
        }
    });
});




//判断页面是否有弹出

$(document).ready(function(){
    $(".Regional").click(function(){
        if ($('.Brand-eject').hasClass('grade-w-roll')){
            $('.Brand-eject').removeClass('grade-w-roll');
        };
    });
});
$(document).ready(function(){
    $(".Regional").click(function(){
        if ($('.Sort-eject').hasClass('grade-w-roll')){
            $('.Sort-eject').removeClass('grade-w-roll');
        };
    });
});
$(document).ready(function(){
    $(".Brand").click(function(){
        if ($('.Sort-eject').hasClass('grade-w-roll')){
            $('.Sort-eject').removeClass('grade-w-roll');
        };
    });
});
$(document).ready(function(){
    $(".Brand").click(function(){
        if ($('.grade-eject').hasClass('grade-w-roll')){
            $('.grade-eject').removeClass('grade-w-roll');
        };
    });
});
$(document).ready(function(){
    $(".Sort").click(function(){
        if ($('.Brand-eject').hasClass('grade-w-roll')){
            $('.Brand-eject').removeClass('grade-w-roll');
        };

    });
});
$(document).ready(function(){
    $(".Sort").click(function(){
        if ($('.grade-eject').hasClass('grade-w-roll')){
            $('.grade-eject').removeClass('grade-w-roll');
        };

    });
});


//js点击事件监听开始
function grade1(wbj){
    var arr = document.getElementById("gradew").getElementsByTagName("li");
    var quanbu = document.getElementById("all");
    for (var i = 0; i < arr.length; i++){
        var a = arr[i];
        a.style.background = "";
    };
    wbj.style.background = "#eee";
    quanbu.innerHTML=wbj.innerHTML;
}

function gradet(tbj){
    var arr = document.getElementById("gradet").getElementsByTagName("li");
    for (var i = 0; i < arr.length; i++){
        var a = arr[i];
        a.style.background = "";
    };
    tbj.style.background = "#fff";
}

function grades(sbj){
    var arr = document.getElementById("grades").getElementsByTagName("li");
    for (var i = 0; i < arr.length; i++){
        var a = arr[i];
        a.style.borderBottom = "";
    };
    sbj.style.borderBottom = "solid 1px #ff7c08"
}



function Sorts(sbj){
    var arr = document.getElementById("Sort-Sort").getElementsByTagName("li");
    for (var i = 0; i < arr.length; i++){
        var a = arr[i];
        a.style.color = "";
    };
    sbj.style.color = " #ff7c08"

}
function Brand(sbj){
    var arr = document.getElementById("Brand-Sort").getElementsByTagName("li");
    for (var i = 0; i < arr.length; i++){
        var a = arr[i];
        a.style.color = "";
    };
    sbj.style.color = " #ff7c08";

}
