var ksk3d={
  userID : $('#userID' ,parent.document)[0].defaultValue,
  mapID : $('#mapID' ,parent.document)[0].defaultValue,
  dataID : $('#dataID' ,parent.document)[0].defaultValue,
  format : "'3DTiles'"
};

document.getElementById('ksk3d_text_format').innerHTML = 
"<p style='margin:5px 0 5px 20px; color:#333;'>データセット一覧のうち、フォーマットが "+ksk3d.format+" に該当するデータセットを表示しています。</p>";

let ksk3d_foramt = [
  /czml|内部データセット/,
  /3dtiles/
];
