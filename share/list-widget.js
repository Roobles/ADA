// A single row of items to click on.
function ListRow(selectItems) {
	this.Items = selectItems;
}

// A single, clickable item that does something.
function SelectItem(spanValue, onClickHandle) {
	this.SpanValue 		= spanValue;
	this.ClickHandle	= onClickHandle;
}



//
// Select Widget Class
//
function SelectListWidget(divName) {
	//Configurations
	this.iterNo	= 60;
	this.iterThresh	= 20;
	this.timeWait	= 5;
	this.styles	= new Array("TableStyle01", "TableStyle02"); 

	//Properties
	this.isRunning 		= false;
	this.isClearing		= false;
	this.container 		= $("#" + divName);
	this.list		= $("<ul>");
	this.listRowQueue	= null;
	this.clearRowQueue	= null;
	this.priorRows		= null;
	this.iterations 	= 0;
	this.clearCount		= 0;
	this.addTimeoutId	= null;
	this.clearTimeoutId	= null;

	//Methods
	this.Print		= PrintList;
	this.Cancell		= CancellOperation;
	this.AddRows		= AddRows;
	this.AddRow		= AddRow;
	this.CleanUpTail	= CleanUpTail;
	this.ClearRows		= ClearRows;
	this.ClearRow		= ClearRow;
	this.GetIterationCount	= GetIterationCount;

	//Constructor Code
	this.container.append(this.list);
}

function PrintList(rows) {
	this.Cancell();

	this.isRunning 		= true;
	this.iterations		= 0;
	this.listRowQueue	= rows;
	this.priorRows		= this.list.children();

	this.AddRows();
}

function CancellOperation() {
	if(this.isRunning) {
		this.listRowQueue 	= null;
		clearTimeout(this.addTimeoutId);
	}

	if(this.isClearing) {
		this.clearRowQueue	= null;
		clearTimeout(this.clearTimeoutId);
	}
}

function AddRows() {
	if (this.listRowQueue.length < 1) {
		this.isRunning   = false;
		this.CleanUpTail();
		return;
	}

	var iters = this.GetIterationCount(this.listRowQueue.length, this.iterations);

	for(var i=0; i < iters; i++) {
		++this.iterations;

		var row		= this.listRowQueue.shift();
		var className	= this.styles[this.iterations % this.styles.length];

		this.AddRow(BuildListRow(row, className));
	}

	var addy 		= ClosureAddRows(this);
	this.addTimeoutId	= setTimeout(addy, this.timeWait);
}

function AddRow(row) {
	if(this.iterations <= this.priorRows.length)
		this.priorRows.eq(this.iterations -1).replaceWith(row);
	else
		this.list.append(row);
}

function ClosureAddRows(selectWidget) {

	var addRowsClosureFunction = function() {
		selectWidget.AddRows();
	}

	return addRowsClosureFunction;
}

function ClosureClearRows(selectWidget) {
	var clearRowsClosureFunction = function() {
		selectWidget.ClearRows();
	}

	return clearRowsClosureFunction;
}

function CleanUpTail() {
	this.clearCount	= 0;
	var tailLength 	= this.priorRows.length - this.iterations;

	if(tailLength > 0) {
		this.clearRowQueue = this.priorRows.slice(this.iterations, this.priorRows.length);
		this.ClearRows();
	}
}

function ClearRows() {
	var rowsRemaining = this.clearRowQueue.length - this.clearCount;

	if (rowsRemaining < 1) {
		this.isClearing	= false;
		return;
	}

	var iters = this.GetIterationCount(rowsRemaining);

	for(var i=iters; i > 0; i--) {
		var clearRowPos 	= this.clearRowQueue.length - this.clearCount++ -1;
		var clearRow		= this.clearRowQueue.eq(clearRowPos);

		ClearRow(clearRow);
	}

	var clearClos 		= ClosureClearRows(this);
	this.clearTimeoutId	= setTimeout(clearClos, this.timeWait);
}

function GetIterationCount(rowsRemaining, iterationCount) {

	if(iterationCount != undefined) {
		if(iterationCount < 1) 
			iterationCount = 1;

		if(iterationCount < this.iterThresh && iterationCount < rowsRemaining) 
			rowsRemaining = iterationCount;
	}

	var iterInc	= Math.ceil(rowsRemaining / this.iterThresh);
	var iterNo	= iterInc > this.iterNo ? this.iterNo : iterInc;

	return rowsRemaining > iterNo ? iterNo : rowsRemaining;
}

function ClearRow(row) {
	row.remove();
}

function BuildListRow(listRow, className) {
	var row = $("<li>").addClass(className);
	
	for(var i=0; i<listRow.Items.length; i++)
		row.append(BuildListItem(listRow.Items[i]));

	return row;
}

function BuildListItem(item) {
	return $("<span>").click(item.ClickHandle).addClass('clickable').append(item.SpanValue);
}
