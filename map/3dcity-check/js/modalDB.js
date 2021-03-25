$(function(){
  $("#dataAdditionModal").iziModal({
    padding: 10
  });
});

var viewModel_izi = {
  dataLists: ko.observable([])
}

function importDB_data(){
  return $.ajax({	
    url:'./php/import_data.php',
    type:'POST',
    datatype: 'json',
    data:{
     'userID' : ksk3d.userID,
     'format' : ksk3d.format
    }
  })
}

importDB_data().then(function(data) {
  viewModel_izi.dataLists = JSON.parse(data);
  var toolbar_izi = document.getElementById('dataAdditionModal');
  ko.applyBindings(viewModel_izi, toolbar_izi);
});


