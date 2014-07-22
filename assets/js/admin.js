$(function () {


var $studentsList = $("#students-list"),
	$searchBtn = $('#search-btn');


  $('#students-list').on('click','.show-edit-box',function () {
    $(this).parent().parent().next().find('.edit-box').slideToggle();
  });






  $('#search-btn').on('click',loadData);

  function getParams () {
  	var $recordLimitSelect = $('#row-limit'),
  		$studentId = $("#student_id"),
  		$email = $('#user-email'),
  		$grade = $("#grade"),
  		$department = $("#department"),
  		$major = $("#major"),
  		$subMajor = $("#sub-major"),
  		$classes = $('#class-list');

  		var param = {
  			'doSearch': 'Search',
  			'q' : '',
  			'qoption' : '',
  			'record_limit' : $recordLimitSelect.val(),
  			'student_id' : $studentId.val(),
  			'user_email' : $email.val(),
  			'grade' : $grade.val(),
  			'department': $department.val(),
  			'major' : $major.val(),
  			// 'major' : '物理污染监测技术',
  			'sub_major' : $subMajor.val(),
  			'class' : $classes.val()

  		}


  		return param;
  }

  function loadData () {
  	$searchBtn.prop('disabled','true');
  	$studentsList.prepend("<tr class='loading'><td colspan='11'>Loading...</td></tr>");
  	var param = getParams();

  	var numPerPage = parseInt(param['record_limit']);
  	param['record_limit'] = numPerPage; 

  	if(numPerPage <= 20){
  		$.get('search.php',param,function(data){
  			$searchBtn.prop('disabled','');
  			$studentsList.empty().append(data);
  		})
  	}else{
  		$studentsList.empty();
  		param['maxPage'] = parseInt(param['record_limit']/10);
  		param['page'] = 1;
  		loadDataBathes(param);
  	}
  	
  }

  function loadDataBathes(param) {
  	param['record_limit'] = 10;

  	if(param['page']<=param['maxPage']){
  		$.get('search.php',param,function(data){
  				$studentsList.append(data);
  				param['page'] = param['page'] + 1;
  				// loadDataBathes(param);
  				setTimeout(function () {
  					loadDataBathes(param);
  				},2000);
  		})
  	}else{
  		$studentsList.find('.loading').remove();
  	}
  	
  }



  $("#row-limit,#grade").change(loadData);


  $studentsList.on('click','.associate-btn',function () {
    var relid = $(this).attr('relid');
    var $this = $(this);
    $.get("do.php",{
      cmd:"associate",
      id : relid
    },function (data) {
      console.log(data);
    })
  });


  $("#doAllocation").click(function () {
    var ids = getCheckAllRowsId();
    console.log(ids);

    if(ids.length){
      $.get("do.php",{
        cmd:"associate",
        id : ids
      },function () {
        // $this.text("ok");
      })
    }

  });

  function getCheckAllRowsId () {
    var $checkedRecords = $studentsList.find(".record-check:checked");
    var ids = [];
    for (var i = 0; i < $checkedRecords.length; i++) {
      ids.push($checkedRecords[i].value);
    };

    return ids.join(',');
  }


  $("#checkall").on('click',function () {
    $studentsList.find(".record-check").prop("checked",true);
  });

  $("#uncheckall").on('click',function () {
    $studentsList.find(".record-check").prop("checked",false);
  });









});






































