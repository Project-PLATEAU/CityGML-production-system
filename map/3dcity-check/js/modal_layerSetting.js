var layerSetting = {};

$(".iziModal_group").iziModal({
  title: 'データセット設定',
  padding: 10,
  group: "group1",
  loop: true,
  transitionIn: 'fadeInLeft',
});

$("#layerSettingModal23").iziModal({
  title: 'データセット設定',
  padding: 10,
  group: "group1",
  loop: true,
  transitionIn: 'fadeInLeft',
  iframe: true,
  iframeWidth:400,
  iframeHeight: 300,
  iframeURL: function(){
    return "./php/modal_layerSetting_style.php?userID=" +ksk3d.userID +"&mapID="+ksk3d.mapID+"&layerID="+ksk3d.layerID+"&file_format="+ksk3d.file_format
  }
});

$(document).on('click', '#btn-layer-setting', function (event) {
  event.preventDefault();
  id =  $(this).attr("value");
  layerSetting = viewModel.contents[id];
  layerSetting.id = id;

  var elem;
  var elem = document.getElementById('ls_display_name');
  elem.textContent = layerSetting.display_name;

  var elem = document.getElementById('ls_file_id');
  elem.textContent = layerSetting.file_id;

  var elem = document.getElementById('ls_file_format');
  elem.textContent = layerSetting.file_format;

  elem = document.getElementById('ls_zoomToDataset');
  elem.onclick = new Function("ksk3d_zoomToDataset("+id+");");

  elem = document.getElementById('ls_datasetRemove');
  elem.onclick = new Function("ksk3d_datasetRemove("+id+");");

  ksk3d.layerID = Number(id) + 1;
  ksk3d.file_format =layerSetting.file_format.toLowerCase();
  $('.iziModal_group').iziModal('open');
});

function hexToRgb(hex) {
  var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
  hex = hex.replace(shorthandRegex, function(m, r, g, b) {
    return r + r + g + g + b + b;
  });

  var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
  return result ? {
    r: parseInt(result[1], 16),
    g: parseInt(result[2], 16),
    b: parseInt(result[3], 16)
  } : null;
}

function iziModal_group_ok(id=""){
  var elem;
  var text_set;

  file_format = viewModel.contents[layerSetting.id].file_format.toLowerCase();
  console.log("file_format:"+file_format);
  
  if (id==""){
    elem = document.getElementById('ls_display_name');
    if (elem.textContent != layerSetting.display_name) {
      text_set = "display_name = '"+elem.textContent +"'";
    }
  } else {
    $.ajax({
      url:'./php/import_layer_style.php',
      type:'POST',
      datatype: 'json',
      data:{
       'userID' : ksk3d.userID,
       'mapID' : ksk3d.mapID,
       'layerID' : Number(layerSetting.id)+1
      }
    }).done(function(data,textStatus,jqXHR) {
      if (data != "") {
        var v = JSON.parse(data);
        console.log(v[0].color_exp)
        if ((v[0].color_exp != '') && (typeof v[0].color_exp !== "undefined")){
          ksk3d_style(viewModel.contents[layerSetting.id].dataSource ,v[0].color_exp ,file_format);
        }
      }
    }).fail(function(jqXHR, textStatus, errorThrown){
      console.log(textStatus);
      console.log(jqXHR);
    });
  }

  if (id==""){
    $('.iziModal_group').iziModal('close');
  } else {
    $('#'+id).iziModal('close');
  }
}

function iziModal_group_cancel(id=""){
  console.log("iziModal_group_cancel:"+id);
  if (id==""){
    $('.iziModal_group').iziModal('close');
  } else {
    $('#'+id).iziModal('close');
  }
}
