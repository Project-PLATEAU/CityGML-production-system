require.config({baseUrl : './../../Source',waitSeconds : 60});
if (typeof Cesium !== 'undefined') {
  'use strict';
var viewer = new Cesium.Viewer('cesiumContainer', {
    imageryProvider: new Cesium.UrlTemplateImageryProvider({
      url: 'https://cyberjapandata.gsi.go.jp/xyz/pale/{z}/{x}/{y}.png',
      credit: new Cesium.Credit('地理院タイル', '', 'https://maps.gsi.go.jp/development/ichiran.html')
    }),
    terrainProvider: new Cesium.JapanGSITerrainProvider({}),
    baseLayerPicker: false,
    geocoder: false,
    homeButton: false,
    sceneModePicker: false,
    vrButton : true
});
var imageryLayers = viewer.imageryLayers;
viewer.scene.globe.depthTestAgainstTerrain = true;
viewer.scene.globe.baseColor = Cesium.Color.WHITE;
var viewModel = {
    layers : [],
    contents : [],
    baseLayers : [],
    selectedLayer : null,
    isSelectableLayer : function(layer) {
        return this.baseLayers.indexOf(layer) >= 0;
    },
    raise : function(layer, index) {
        imageryLayers.raise(layer);
        viewModel.upLayer = layer;
        viewModel.downLayer = viewModel.layers[Math.max(0, index - 1)];
        updateLayerList();
        window.setTimeout(function() { viewModel.upLayer = viewModel.downLayer = null; }, 10);
    },
    lower : function(layer, index) {
        imageryLayers.lower(layer);
        viewModel.upLayer = viewModel.layers[Math.min(viewModel.layers.length - 1, index + 1)];
        viewModel.downLayer = layer;
        updateLayerList();
        window.setTimeout(function() { viewModel.upLayer = viewModel.downLayer = null; }, 10);
    },
    canRaise : function(layerIndex) {
        return layerIndex > 0;
    },
    canLower : function(layerIndex) {
        return layerIndex >= 0 && layerIndex < imageryLayers.length - 1;
    },
    show_1 : true,
    select_1 : 0,
    selected_terrain : 31
};
var baseLayers = viewModel.baseLayers;
Cesium.knockout.track(viewModel);
var loadTileList = [];
function setupLayers() {
    addBaseLayerOption(
            '淡色地図',
            undefined);
    addBaseLayerOption(
            '標準地図',
             new Cesium.UrlTemplateImageryProvider({
                url: 'https://cyberjapandata.gsi.go.jp/xyz/std/{z}/{x}/{y}.png',
                credit: new Cesium.Credit('地理院タイル', '', 'https://maps.gsi.go.jp/development/ichiran.html')
            }));
    addBaseLayerOption(
            '白地図',
             new Cesium.UrlTemplateImageryProvider({
                url: 'https://cyberjapandata.gsi.go.jp/xyz/blank/{z}/{x}/{y}.png',
                credit: new Cesium.Credit('地理院タイル', '', 'https://maps.gsi.go.jp/development/ichiran.html')
            }));
    addBaseLayerOption(
            '標準地図(英語)',
             new Cesium.UrlTemplateImageryProvider({
                url: 'https://cyberjapandata.gsi.go.jp/xyz/english/{z}/{x}/{y}.png',
                credit: new Cesium.Credit('地理院タイル', '', 'https://maps.gsi.go.jp/development/ichiran.html')
            }));
    addBaseLayerOption(
            '写真',
             new Cesium.UrlTemplateImageryProvider({
                url: 'https://cyberjapandata.gsi.go.jp/xyz/seamlessphoto/{z}/{x}/{y}.jpg',
                fileExtension: 'jpg',
                credit: new Cesium.Credit('地理院タイル,Landsat8画像（GSI,TSIC,GEO Grid/AIST）, Landsat8画像（courtesy of the U.S. Geological Survey）, 海底地形（GEBCO）,Images on 世界衛星モザイク画像 obtained from site https://lpdaac.usgs.gov/data_access maintained by the NASA Land Processes Distributed Active Archive Center (LP DAAC), USGS/Earth Resources Observation and Science (EROS) Center, Sioux Falls, South Dakota, (Year). Source of image data product.', '', 'https://maps.gsi.go.jp/development/ichiran.html')
            }));
}
function addBaseLayerOption(name, imageryProvider) {
    var layer;
    if (typeof imageryProvider === 'undefined') {
        layer = imageryLayers.get(0);
        viewModel.selectedLayer = layer;
    } else {
        layer = new Cesium.ImageryLayer(imageryProvider);
    }
    layer.name = name;
    baseLayers.push(layer);
}
function addAdditionalLayerOption(name, imageryProvider, alpha, show) {
    var layer = imageryLayers.addImageryProvider(imageryProvider);
    layer.alpha = Cesium.defaultValue(alpha, 0.5);
    layer.show = Cesium.defaultValue(show, true);
    layer.name = name;
    Cesium.knockout.track(layer, ['alpha', 'show', 'name']);
}
function updateLayerList() {
    var numLayers = imageryLayers.length;
    viewModel.layers.splice(0, viewModel.layers.length);
    for (var i = numLayers - 1; i >= 0; --i) {
        viewModel.layers.push(imageryLayers.get(i));
    }
}
setupLayers();
updateLayerList();
var toolbar = document.getElementById('toolbar');
Cesium.knockout.applyBindings(viewModel, toolbar);
Cesium.knockout.getObservable(viewModel, 'selectedLayer').subscribe(function(baseLayer) {
    var activeLayerIndex = 0;
    var numLayers = viewModel.layers.length;
    for (var i = 0; i < numLayers; ++i) {
        if (viewModel.isSelectableLayer(viewModel.layers[i])) {
            activeLayerIndex = i;
            break;
        }
    }
    var activeLayer = viewModel.layers[activeLayerIndex];
    var show = activeLayer.show;
    var alpha = activeLayer.alpha;
    imageryLayers.remove(activeLayer, false);
    imageryLayers.add(baseLayer, numLayers - activeLayerIndex - 1);
    baseLayer.show = show;
    baseLayer.alpha = alpha;
    updateLayerList();
});
Cesium.knockout.getObservable(viewModel, 'select_1').subscribe(function(select_1) {
	data1_load(viewModel.select_1);
});
Cesium.knockout.getObservable(viewModel, 'selected_terrain').subscribe(function(selected_terrain) {
    if (viewModel.selected_terrain==2) {
      viewer.terrainProvider = new Cesium.EllipsoidTerrainProvider({});
      viewer.scene.globe.depthTestAgainstTerrain = false;
    } else if (viewModel.selected_terrain==30) {
      viewer.terrainProvider = new Cesium.JapanGSITerrainProvider({});
      viewer.scene.globe.depthTestAgainstTerrain = false;
    } else if (viewModel.selected_terrain==31) {
      viewer.terrainProvider = new Cesium.JapanGSITerrainProvider({});
      viewer.scene.globe.depthTestAgainstTerrain = true;
    }
});
var nameOverlay = document.createElement('div');
viewer.container.appendChild(nameOverlay);
nameOverlay.className = 'backdrop';
nameOverlay.style.display = 'none';
nameOverlay.style.position = 'absolute';
nameOverlay.style.bottom = '0';
nameOverlay.style.left = '0';
nameOverlay.style['pointer-events'] = 'none';
nameOverlay.style.padding = '4px';
nameOverlay.style.backgroundColor = 'black';
var selected = {
    feature: undefined,
    originalColor: new Cesium.Color()
};
var selectedEntity = new Cesium.Entity();
var clickHandler = viewer.screenSpaceEventHandler.getInputAction(Cesium.ScreenSpaceEventType.LEFT_CLICK);
if (Cesium.PostProcessStageLibrary.isSilhouetteSupported(viewer.scene)) {
    var silhouetteBlue = Cesium.PostProcessStageLibrary.createEdgeDetectionStage();
    silhouetteBlue.uniforms.color = Cesium.Color.BLUE;
    silhouetteBlue.uniforms.length = 0.01;
    silhouetteBlue.selected = [];
    var silhouetteGreen = Cesium.PostProcessStageLibrary.createEdgeDetectionStage();
    silhouetteGreen.uniforms.color = Cesium.Color.LIME;
    silhouetteGreen.uniforms.length = 0.01;
    silhouetteGreen.selected = [];
    viewer.scene.postProcessStages.add(Cesium.PostProcessStageLibrary.createSilhouetteStage([silhouetteBlue, silhouetteGreen]));
    viewer.screenSpaceEventHandler.setInputAction(function onMouseMove(movement) {
      silhouetteBlue.selected = [];
      var pickedFeature = viewer.scene.pick(movement.endPosition);
      if (!Cesium.defined(pickedFeature)) {
          nameOverlay.style.display = 'none';
          return;
      }
      nameOverlay.style.display = 'block';
      nameOverlay.style.bottom = viewer.canvas.clientHeight - movement.endPosition.y + 'px';
      nameOverlay.style.left = movement.endPosition.x + 'px';
      var name;
      if (typeof pickedFeature.id !== 'undefined'){
        if (typeof pickedFeature.id._name !== 'undefined') {
          name = pickedFeature.id._name;
        } else {
          name = pickedFeature.id._id;
        }
      } else {
        name = pickedFeature.getProperty('name');
        if (!Cesium.defined(name)) {
            name = pickedFeature.getProperty('id');
        }
      }
      nameOverlay.textContent = name;
      if (pickedFeature !== selected.feature) {
          silhouetteBlue.selected = [pickedFeature];
      }
  }, Cesium.ScreenSpaceEventType.MOUSE_MOVE);
  viewer.screenSpaceEventHandler.setInputAction(function onLeftClick(movement) {
    silhouetteGreen.selected = [];
    var pickedFeature = viewer.scene.pick(movement.position);
    if (!Cesium.defined(pickedFeature)) {
        clickHandler(movement);
        return;
    }
    if (typeof pickedFeature.id !== 'undefined'){
      if (typeof pickedFeature.id.kml !== 'undefined'){
        clickHandler(movement);
        return;
      }
    }
    if (silhouetteGreen.selected[0] === pickedFeature) {
        return;
    }
    var highlightedFeature = silhouetteBlue.selected[0];
    if (pickedFeature === highlightedFeature) {
        silhouetteBlue.selected = [];
    }
    silhouetteGreen.selected = [pickedFeature];
    var featureName;
    if (typeof pickedFeature.id !== 'undefined'){
      featureName = pickedFeature.id._name;
      if (!Cesium.defined(featureName)) {
          featureName = pickedFeature.id.id;
      }
    } else {
      featureName = pickedFeature.getProperty('name');
      if (!Cesium.defined(featureName)) {
          featureName = pickedFeature.getProperty('id');
      }
    }
    selectedEntity.name = featureName;
    selectedEntity.description = 'Loading <div class="cesium-infoBox-loading"></div>';
    viewer.selectedEntity = selectedEntity;
    var attr = '<table class="cesium-infoBox-defaultTable"><tbody>';
    if (typeof pickedFeature.id !== 'undefined'){
      var propertyNames = pickedFeature.id._properties.propertyNames;
      var length = propertyNames.length;
      for (var i = 0; i < length; ++i) {
        var propertyName = propertyNames[i];
        var propertyValue = pickedFeature.id._properties[propertyName]._value;
        if (propertyValue == undefined) {
          var propertyValue1 = pickedFeature.id._properties[propertyName];
          var length2 = propertyValue1._times.length;
          for (var i2 = 0; i2 < length2; ++i2) {
            var propertyName2 = propertyValue1._times[i2];
            var propertyValue2 = propertyValue1._values[i2];
            attr += '<tr><th>'+propertyName+'('+propertyName2+')</th><td>' + propertyValue2 + '</td></tr>';
          }
        } else {
          attr += '<tr><th>'+propertyName+'</th><td>' + propertyValue + '</td></tr>';
        }
      }
    } else {
      var propertyNames = pickedFeature.getPropertyNames();
      var length = propertyNames.length;
      for (var i = 0; i < length; ++i) {
        var propertyName = propertyNames[i];
        attr += '<tr><th>'+propertyName+'</th><td>' + pickedFeature.getProperty(propertyName) + '</td></tr>';
      }
    }
    attr += '</tbody></table>';
    selectedEntity.description = attr;
  }, Cesium.ScreenSpaceEventType.LEFT_CLICK);
} else {
  var highlighted = {
      feature : undefined,
      originalColor : new Cesium.Color()
  };
  viewer.screenSpaceEventHandler.setInputAction(function onMouseMove(movement) {
    if (Cesium.defined(highlighted.feature)) {
      highlighted.feature.color = highlighted.originalColor;
      highlighted.feature = undefined;
    }
    var pickedFeature = viewer.scene.pick(movement.position);
    if (!Cesium.defined(pickedFeature)) {
        nameOverlay.style.display = 'none';
        return;
    }
    nameOverlay.style.display = 'block';
    nameOverlay.style.bottom = viewer.canvas.clientHeight - movement.endPosition.y + 'px';
    nameOverlay.style.left = movement.endPosition.x + 'px';
    if (typeof pickedFeature.id !== 'undefined'){
      if (typeof pickedFeature.id._name !== 'undefined') {
        name = pickedFeature.id._name;
      } else {
        name = pickedFeature.id._id;
      }
    } else {
      name = pickedFeature.getProperty('name');
      if (!Cesium.defined(name)) {
          name = pickedFeature.getProperty('id');
      }
    }
    nameOverlay.textContent = name;
    if (pickedFeature !== selected.feature) {
      highlighted.feature = pickedFeature;
      Cesium.Color.clone(pickedFeature.color, highlighted.originalColor);
      pickedFeature.color = Cesium.Color.YELLOW;
    }
  }, Cesium.ScreenSpaceEventType.MOUSE_MOVE);
    viewer.screenSpaceEventHandler.setInputAction(function onLeftClick(movement) {
    if (Cesium.defined(selected.feature)) {
      selected.feature.color = selected.originalColor;
      selected.feature = undefined;
    }
    var pickedFeature = viewer.scene.pick(movement.position);
    if (!Cesium.defined(pickedFeature)) {
      clickHandler(movement);
      return;
    }
    if (typeof pickedFeature.id !== 'undefined'){
      if (typeof pickedFeature.id.kml !== 'undefined'){
        clickHandler(movement);
        return;
      }
    }
    if (selected.feature === pickedFeature) {
        return;
    }
    selected.feature = pickedFeature;
    if (pickedFeature === highlighted.feature) {
      Cesium.Color.clone(highlighted.originalColor, selected.originalColor);
      highlighted.feature = undefined;
    } else {
      Cesium.Color.clone(pickedFeature.color, selected.originalColor);
    }
    pickedFeature.color = Cesium.Color.LIME;
    var featureName;
    if (typeof pickedFeature.id !== 'undefined'){
      featureName = pickedFeature.id._name;
      if (!Cesium.defined(featureName)) {
          featureName = pickedFeature.id.id;
      }
    } else {
      featureName = pickedFeature.getProperty('name');
      if (!Cesium.defined(featureName)) {
          featureName = pickedFeature.getProperty('id');
      }
    }
    selectedEntity.name = featureName;
    selectedEntity.description = 'Loading <div class="cesium-infoBox-loading"></div>';
    viewer.selectedEntity = selectedEntity;
    var attr = '<table class="cesium-infoBox-defaultTable"><tbody>';
    if (typeof pickedFeature.id !== 'undefined'){
      var propertyNames = pickedFeature.id._properties.propertyNames;
      var length = propertyNames.length;
      for (var i = 0; i < length; ++i) {
        var propertyName = propertyNames[i];
        var propertyValue = pickedFeature.id._properties[propertyName]._value;
        if (propertyValue == undefined) {
          var propertyValue1 = pickedFeature.id._properties[propertyName];
          var length2 = propertyValue1._times.length;
          for (var i2 = 0; i2 < length2; ++i2) {
            var propertyName2 = propertyValue1._times[i2];
            var propertyValue2 = propertyValue1._values[i2];
            attr += '<tr><th>'+propertyName+'('+propertyName2+')</th><td>' + propertyValue2 + '</td></tr>';
          }
        } else {
          attr += '<tr><th>'+propertyName+'</th><td>' + propertyValue + '</td></tr>';
        }
      }
    } else {
      var propertyNames = pickedFeature.getPropertyNames();
      var length = propertyNames.length;
      for (var i = 0; i < length; ++i) {
        var propertyName = propertyNames[i];
        attr += '<tr><th>'+propertyName+'</th><td>' + pickedFeature.getProperty(propertyName) + '</td></tr>';
      }
    }
    attr += '</tbody></table>';
    selectedEntity.description = attr;
  }, Cesium.ScreenSpaceEventType.LEFT_CLICK);
}
var intervalHandle;
function ksk3d_loadTile(){
  if (loadTileList.length==0){
    intervalHandle = "";
  } else {
    intervalHandle = setInterval(function() {
      let client_w = document.getElementById('cesiumContainer').clientWidth;
      let client_h = document.getElementById('cesiumContainer').clientHeight;
      var centerPosition = new Cesium.Cartesian2(client_w/2, client_h/2); 
      var ellipsoid = viewer.scene.globe.ellipsoid;
      var cartesian = viewer.camera.pickEllipsoid(centerPosition, ellipsoid);
      if (cartesian) {
          var cartographic = ellipsoid.cartesianToCartographic(cartesian);
      } else {
          var cartographic = viewer.camera.positionCartographic;
      }
      var Lon = Cesium.Math.toDegrees(cartographic.longitude).toFixed(5);
      var Lat = Cesium.Math.toDegrees(cartographic.latitude).toFixed(5); 
      var code = (Lat *1.5 +'').substr(0 ,2);
      Lat -= code /1.5;
      code += (Lon +'').substr(1 ,2);
      Lon -= Math.floor(Lon);
      code += (Lat *12 +'').substr(0 ,1);
      Lat -= code.substr(4 ,1) /12;
      code += (Lon *8 +'').substr(0 ,1);
      Lon -= code.substr(5 ,1) /8;
      code += (Lat *120 +'').substr(0 ,1);
      Lat -= code.substr(6 ,1) /120;
      code += (Lon *80 +'').substr(0 ,1);
      Lon -= code.substr(7 ,1) /80;
      meshcode = code;
      if (!meshcode.match(/[0-9]+/)){
        return;
      }
      loadTileList.forEach(function( id ) {
        if (typeof viewModel.contents[id].loadedTile[meshcode] == 'undefined') {
          viewModel.contents[id].loadedTile[meshcode]=1;
          dataset = viewModel.contents[id];
          $.ajax({
            url:'./php/select_geom_mesh.php',
            type:'POST',
            datatype: 'json',
            data:{
             'userID' : ksk3d.userID,
             'fileID' : dataset.file_id,
             'meshcode' : meshcode
            }
          }).done(function(data,textStatus,jqXHR) {
            if (data != ']'){
              var rows = JSON.parse(data);
              var pattern = /[0-9., ]/g;
              rows.forEach(function( value ) {
                var sgs = value.the_geom.match(/\(\([0-9., \(\)]+?\)\)/g);
                sgs.forEach(function( sg ) {
                  var sls = sg.match(/\([0-9., ]+?\)/g);
                  var eg = sls[0].replace( /([\d.]+) ([\d.]+)/g, '$1,$2,0').replace(/\(|\)/g ,'').split(',');
                  viewModel.contents[id].czml.push({
                    "id" : value.id,
                    "properties" : {"id" : value.id},
                    "description" : "<table style='font-family:Arial,Verdana,Times;font-size:12px;text-align:left;width:100%;border-spacing:0px; padding:3px 3px 3px 3px'><tr><td>Meshcode</td><td>"+"10"+"</td></tr><tr><td>階数</td><td>"+"2"+"</td></tr></table>",
                    "polygon" : {
                      "positions" : {"cartographicDegrees" : eg},
                      "heightReference" : "CLAMP_TO_GROUND",
                      "extrudedHeight" : value.m,
                      "perPositionHeight" : false,
                      "width" : 0,
                      "cornerType": "BEVELED",
                      "material" : {
                        "solidColor" : {
                          "color" : {
                            "rgba" : [128, 128, 128, 255]
                          }
                        }
                      }
                    }
                  });
                  if (sls.length>1) {
                    sls.shift();
                    for(let i = 0; i < sls.length; i++) {
                      sls[i] = sls[i].replace( /([\d.]+) ([\d.]+)/g, '$1,$2,0').replace(/\(|\)/g ,'').split(',');
                    }
                    viewModel.contents[id].czml[viewModel.contents[id].czml.length-1]['polygon']['holes'] = {"cartographicDegrees" : sls};
                  }
                });
              });
              viewModel.contents[id].dataSource.load(viewModel.contents[id].czml);
            } else {
            }
          }).fail(function(jqXHR, textStatus, errorThrown){
          });
        }
      });
    }, 1000);
  }
}
function ksk3d_CesiumDataSourceAdd() {
  var id = viewModel.contents.length-1;
  var dataset = viewModel.contents[id];
  var file_format = dataset.file_format.toLowerCase();
  var file_path = dataset.file_path.replace(/(.+)wp-content/ ,'\.\./\.\./wp-content');
  var promise;
  if (file_format=='czml') {
    viewModel.contents[id].dataSource = new Cesium.CzmlDataSource();
    viewModel.contents[id].dataSource.load (file_path +'/' +dataset.file_name);
    viewer.dataSources.add(viewModel.contents[id].dataSource);
    if (viewModel.contents[id].color_exp!=""){
      ksk3d_style(viewModel.contents[id].dataSource,viewModel.contents[id].color_exp ,file_format);
    }
    viewer.zoomTo(viewModel.contents[id].dataSource, new Cesium.HeadingPitchRange(0, -1, 50000));
  } else if (file_format=='内部データセット') {
    viewModel.contents[id].czml = [{
      "id" : "document",
      "name" : "czml",
      "version" : "1.0"
    }];
    if (dataset.meshsize > 0){
      viewModel.contents[id].dataSource = new Cesium.CzmlDataSource();
      viewModel.contents[id].dataSource.load(viewModel.contents[id].czml);
      viewer.dataSources.add(viewModel.contents[id].dataSource);
      if (viewModel.contents[id].color_exp!=""){
        ksk3d_style(viewModel.contents[id].dataSource,viewModel.contents[id].color_exp ,file_format);
      }
      var pos = dataset.camera_position;
      console.log("pos:"+pos);
      if (!pos.match(/[\-0-9\.]+,[\-0-9\.]+,[0-9\.]+/)){
        pos = "133,33,500000";
      }
      [pos_x ,pos_y ,pos_z] = pos.split(',');
      viewer.camera.setView({
          destination: Cesium.Cartesian3.fromDegrees(pos_x ,pos_y ,pos_z)
      });
      viewModel.contents[id].loadedTile = [];
      loadTileList.push(id);
      if (loadTileList.length==1) {
        ksk3d_loadTile();
      }
    } else {
      $.ajax({
        url:'./php/select_geom.php',
        type:'POST',
        datatype: 'json',
        data:{
         'userID' : ksk3d.userID,
         'fileID' : dataset.file_id
        }
      }).done(function(data,textStatus,jqXHR) {
        if (data.match(/Fatal error/i)){
          var err = data.match(/:(.+?) in /i);
          alert("エラーがあります。データセットを見直してください。\n"+err[1]);
        } else if (data != ']'){
          var tmp = data;
          var rows = JSON.parse(data);
          var pattern = /[0-9., ]/g;
          rows.forEach(function( value ) {
            if (value.id % 1000 == 0){console.log("geometryID:"+value.id);}
            var sgs = value.the_geom.match(/\(\([0-9., \(\)]+?\)\)/g);
            var i_hole = 0;
            sgs.forEach(function( sg ) {
              var sls = sg.match(/[0-9., ]+/g);
              for(let i = 0; i < sls.length; i++) {
                var eg = sls[i].replace( /([\d.]+) ([\d.]+)/g, '$1,$2,0').replace(/\(|\)/g ,'').split(',');
                viewModel.contents[id].czml.push({
                  "id" : value.id*1000+i,
                  "properties" : {"id" : value.id},
                  "description" : "<table style='font-family:Arial,Verdana,Times;font-size:12px;text-align:left;width:100%;border-spacing:0px; padding:3px 3px 3px 3px'><tr><td>Meshcode</td><td>"+"10"+"</td></tr><tr><td>階数</td><td>"+"2"+"</td></tr></table>",
                  "polygon" : {
                    "positions" : {"cartographicDegrees" : eg},
                    "heightReference" : "CLAMP_TO_GROUND",
                    "extrudedHeight" : value.m,
                    "perPositionHeight" : false,
                    "width" : 0,
                    "cornerType": "BEVELED",
                    "material" : {
                      "solidColor" : {
                        "color" : {
                          "rgba" : [128, 128, 128, 255]
                        }
                      }
                    }
                  }
                });
                if (i_hole==0){
                  var hole = value.hole.match(/\(\([0-9., \(\)]+?\)\)/g);
                  if (hole!==null){
                    hole.forEach(function( holeg ) {
                      var sls_hole = holeg.match(/[0-9., ]/g);
                      for(let i = 0; i < sls_hole.length; i++) {
                        sls_hole[i] = sls_hole[i].replace( /([\d.]+) ([\d.]+)/g, '$1,$2,0').replace(/\(|\)/g ,'').split(',');
                      }
                      viewModel.contents[id].czml[viewModel.contents[id].czml.length-1]['polygon']['holes'] = {"cartographicDegrees" : sls_hole};
                    });
                  }
                  i_hole = 1;
                }

              }
            });
          });
          viewModel.contents[id].dataSource = new Cesium.CzmlDataSource();
          viewModel.contents[id].dataSource.load(viewModel.contents[id].czml);
          viewer.dataSources.add(viewModel.contents[id].dataSource);
          if (viewModel.contents[id].color_exp!=""){
            ksk3d_style(viewModel.contents[id].dataSource,dataset.color_exp ,file_format);
          }
          viewer.zoomTo(viewModel.contents[id].dataSource, new Cesium.HeadingPitchRange(0, -1, 50000));
        }
      }).fail(function(jqXHR, textStatus, errorThrown){
      });
    }
  } else if (file_format=='3dtiles') {
    viewModel.contents[id].dataSource = viewer.scene.primitives.add(
      new Cesium.Cesium3DTileset({ url: file_path +'/' +dataset.file_name})
    );
    ksk3d_style(viewModel.contents[id].dataSource,viewModel.contents[id].color_exp ,file_format);
    viewer.zoomTo(viewModel.contents[id].dataSource, new Cesium.HeadingPitchRange(0, -1, 50000));
  } else if (file_format=='kml'){
    viewModel.contents[id].dataSource = new Cesium.KmlDataSource();
    viewModel.contents[id].dataSource.load (file_path +'/' +dataset.file_name, {
      markerSymbol: '?',
    });
    viewer.dataSources.add(viewModel.contents[id].dataSource);
    viewer.zoomTo(viewModel.contents[id].dataSource, new Cesium.HeadingPitchRange(0, -1, 50000));
  }
}
function ksk3d_contentAdd(data) {
  viewModel.contents.push({
    "id" : data.id,
    "file_id" : data.file_id,
    "display_name" : data.display_name,
    "file_format" : data.file_format,
    "file_path" : data.file_path,
    "file_name" : data.file_name,
    "color_exp" : data.color_exp,
    "meshsize" : data.meshsize,
    "camera_position" : data.camera_position,
    "visible": true
  });
  ksk3d_CesiumDataSourceAdd();
}
function ksk3d_datasetAdd(data) {
console.log("ksk3d_datasetAdd");
console.log(data);

  $.ajax({
    url:'./php/insert_layer.php', 
    type:'POST',
    data:{
     'userID' : ksk3d.userID,
     'mapID' : ksk3d.mapID,
     'index' : viewModel.contents.length+1,
     'dataset' : data
    }
  }).done(function(data,textStatus,jqXHR) {
  }).fail(function(jqXHR, textStatus, errorThrown){
  });
  ksk3d_contentAdd(data);
}
function ksk3d_loadDbLayer(){
  $.ajax({
    url:'./php/import_layer.php',
    type:'POST',
    datatype: 'json',
    data:{
     'userID' : ksk3d.userID,
     'mapID' : ksk3d.mapID
    }
  }).done(function(data,textStatus,jqXHR) {
    var tmp = data;
    if (data != "") {
      var rows = JSON.parse(data);
      rows.forEach(function( value ) {
        ksk3d_contentAdd({
          id : value.id,
          display_name : value.display_name,
          file_id : value.file_id,
          file_format : value.file_format,
          file_path : value.file_path,
          file_name : value.file_name,
          color_exp : value.color_exp,
          meshsize : value.meshsize,
          camera_position : value.camera_position,
          registDB : false
        });
      });
    } else {
    }
  }).fail(function(jqXHR, textStatus, errorThrown){
  });
}
if (! (typeof ksk3d.mapID === "undefined")){
  ksk3d_loadDbLayer();
}
function ksk3d_datasetRemove(id) {
console.log("ksk3d_datasetRemove");
  $.ajax({
    url:'./php/delete_layer.php',
    type:'POST',
    data:{
     'userID' : ksk3d.userID,
     'mapID' : ksk3d.mapID,
     'index' : parseInt(id)+1
    }
  }).done(function(data,textStatus,jqXHR) {
  }).fail(function(jqXHR, textStatus, errorThrown){
  });
  loadTileList = loadTileList.filter(n => n !== id);
  if (loadTileList.length==0) {
    ksk3d_loadTile();
  }
  viewer.dataSources.remove(viewModel.contents[id].dataSource);
  viewModel.contents.splice(id, 1);
  $('.iziModal_group').iziModal('close');
}
function ksk3d_zoomToDataset(id) {
	viewer.zoomTo(viewModel.contents[id].dataSource);
}
function ksk3d_style(dataSource ,color_exp ,file_format){
  if ((color_exp=="")||(typeof color_exp === "undefined")){
    return false;
  }
  color_exp2 = color_exp.replace(/'/g, '"').replace(/\[&squot\]\[&squot\]/g, "null").replace(/\[&squot\]/g, "'");
  color_exp3 = JSON.parse(color_exp2);
  if (file_format.match(ksk3d_foramt[0])){
    var entities = dataSource.entities.values;
    for (var i = 0, len = entities.length; i < len; i++){
      var entity = entities[i];
      for (let key in color_exp3) {
        if (key.match(/^material$/i)){
          rgba = color_exp3[key].split(',').map(Number);
          entity.polygon.material = new Cesium.Color(rgba[0],rgba[1],rgba[2],rgba[3]);
        } else if (key.match(/^outlineColor$/i)){
          rgba = color_exp3[key].split(',').map(Number);
          entity.polygon.outlineColor = new Cesium.Color(rgba[0],rgba[1],rgba[2],rgba[3]);
        } else if (key.match(/^outline$/i)){
          entity.polygon.outline = color_exp3[key];
        } else if (key.match(/^outlineWidth$/i)){
          entity.polygon.outlineWidth = color_exp3[key];
        }
      }
    }
  } else if (file_format.match(ksk3d_foramt[1])){
    dataSource.style = new Cesium.Cesium3DTileStyle(
      color_exp3
    );
  }
}
Sandcastle.finishedLoading();
} else if (typeof require === 'function') {
    require(['Cesium'], startup);
}
