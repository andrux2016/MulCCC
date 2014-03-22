$().ready(function(){
     formatTable("tb");
 })
  
 //var sortCol;
 var storeTable = {
 
  sortCol:null,
 
  storeTableByColIndex:function(sTableId,colIndex,sType){
 
   var oTable = $("#" + sTableId);  //$("#id")
   var trList = oTable.find("tbody>tr"); //$("tbody>tr")
   var arrTr = trList.get();
   if(storeTable.sortCol==colIndex)
   {
      arrTr.reverse();
   }
   else
   {
       
      arrTr.sort(storeTable.generateCompareTrs(colIndex,sType));
   }
 
   oTable.find("tbody").html($(arrTr));
 
   storeTable.sortCol = colIndex;
 
   formatTable(sTableId);//格式化Table
  },
 
 
  convert:function(sValue,sDataType){
       switch(sDataType)
       {
            case "int":
             return parseInt(sValue,10);
             break;
            case "float":
             return parseFloat(sValue);
             break;
            case "date":
             return new Date(Date.parse(sValue));
             break;
            default:
             return sValue.toString();
       }
  },
 
  generateCompareTrs:function(iCol,sDataType){
   return  function compareTrs(oTr1,oTr2)
   {
      var vValue1 = storeTable.convert(oTr1.children[iCol].innerText,sDataType);  //转换类型
      var vValue2 = storeTable.convert(oTr2.children[iCol].innerText,sDataType);  //转换类型
        if(vValue1 < vValue2) //比较大小
        {
         return -1;
        }
        else if(vValue1> vValue2)
        {
         return 1;
        }
        else
        {
         return 0;
        }
   }
  }
 }
 
 
 //设置页面样式 
 function formatTable(sTableId){
 
   $("#"+sTableId).find("tbody>tr").removeClass("alt");
 
   $(".stripe tr").mouseover(function(){  //jquery .stripe tr mouseover 
 
   $(this).addClass("over");}).mouseout(function(){  //jquery .mouseout 
 
   $(this).removeClass("over");});
 
   $(".stripe tr:even").addClass("alt");
 }
 