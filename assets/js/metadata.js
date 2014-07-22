var metaArray = [{
	'name': 'aaa',
	'major': ['aaa01', 'aaa02', 'aaa03', 'aaa04']
}, {
	'name': 'bbb',
	'major': ['bbb01', 'bbb02', 'bbb03', 'bbb04']
}, {
	'name': 'ccc',
	'major': ['ccc01', 'ccc02', 'ccc03', 'ccc04']
}];

function createMetaData(meta) {
	var metaData = [];

	meta.forEach(function(item, index) {
		var _tmpData = {};
		_tmpData.id = "department_" + (index + 1);
		_tmpData.name = "d_" + item['name'];
		_tmpData.major = [];

		var majors = item['major'];

		majors.forEach(function(m, index) {
			var _tmpMajorData = {};
			_tmpMajorData.id = "major_" + (index + 1);
			_tmpMajorData.name = "m_" + m;
			_tmpData.major.push(_tmpMajorData);
		});

		metaData.push(_tmpData);
	});


	return JSON.stringify(metaData);
}


var metaData = createMetaData(metaArray);


var DEPARTMENTS = [{
	"id": "department_1",
	"name": "d_aaa",
	"major": [{
		"id": "major_1",
		"name": "m_aaa01"
	}, {
		"id": "major_2",
		"name": "m_aaa02"
	}, {
		"id": "major_3",
		"name": "m_aaa03"
	}, {
		"id": "major_4",
		"name": "m_aaa04"
	}]
}, {
	"id": "department_2",
	"name": "d_bbb",
	"major": [{
		"id": "major_1",
		"name": "m_bbb01"
	}, {
		"id": "major_2",
		"name": "m_bbb02"
	}, {
		"id": "major_3",
		"name": "m_bbb03"
	}, {
		"id": "major_4",
		"name": "m_bbb04"
	}]
}, {
	"id": "department_3",
	"name": "d_ccc",
	"major": [{
		"id": "major_1",
		"name": "m_ccc01"
	}, {
		"id": "major_2",
		"name": "m_ccc02"
	}, {
		"id": "major_3",
		"name": "m_ccc03"
	}, {
		"id": "major_4",
		"name": "m_ccc04"
	}]
}];


var DEPARTMENTS = [];


环境工程与土木工程系	环境科学系	环境监测系
机电工程系	生态环境系	环境艺术与服务系
基础教育部	体育部	继续教育部
思想政治理论课教育部	循环经济与低碳经济系