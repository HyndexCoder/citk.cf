class msgbox{
	constructor(pel = 'body'){
		var self = this;
		$(pel).prepend(
			() => {
				self.wrapper = $('<div>', {
					class : 'w3-center',
					style: 'display: none; z-index: 101; position: fixed;left:50%; height:0px;'
				});
				return $('<center>').html(self.wrapper);
			}
		).prepend(
			() => {
				self.backoverlay = $('<div>', {
					style: 'display: none; z-index: 100; position: fixed; width: 100%; height: 100%;background-color: rgba(100,100,100,0.5)'
				});
				return self.backoverlay;
			}
		);

		this.$close=function(){};
	}

	show(options = {}){
		var self = this, opts = {
			title: 'Message title is here',
			body: 'Message body is here',
			footer: '',
			buttons: [],
			closebtn: true,
			easyClose : false,
			afterShow: function(){},
			close: function(){}
		};
		$.extend(opts, options);
	
		var afterShow = opts.afterShow;
		this.$close=opts.close;

		opts.afterShow = function(){
			if(typeof afterShow == 'function') afterShow(arguments);
		}
	
		var main_msg_con;
		self.wrapper.html(() => {
			main_msg_con = $('<div>',{
				class: 'w3-card w3-container w3-white w3-border w3-border w3-round w3-margin-top w3-margin-bottom',
				style: 'overflow:auto;width:100%;min-width:400px;max-width:1000px;left:-50%;position:relative;'
			}).append(
				() => {
					if(opts.closebtn){
						return $('<button>', {
							class: 'w3-btn w3-white w3-right w3-border w3-border-white w3-round',
							style: 'font-weight:bold',
							title : 'Close',
							click: function(){
								self.close();
							}
						}).html(
							$('<i>', {
								class : 'fa fa-times'
							})
						);
					}else return '';
				}
			).append( $('<div>').append(
				()=>{
					this.$title=$('<h3>').html(opts.title);
					return this.$title
				}
			).append($('<div>').append(
				()=>{
					this.$body=$('<div>', {
						style:'overflow:auto;max-height:'+($(window).innerHeight()-150)+'px',
					}).html(opts.body);
					return this.$body;
				}
			).append(function(){
				var buttons_div = $('<div>', {
					class: 'w3-margin'
				});
				opts.buttons.forEach(function(btn){
					var defbtn = {
						text: 'Okay',
						color: 'orange',
						text_color: 'white',
						click: function(){
							self.close();
						}
					};
					$.extend(defbtn, btn);
	
					defbtn.color = defbtn.color == 'main' ? 'main-color main-text-color' : 'w3-'+defbtn.color;
	
					buttons_div.append($('<button>',{
						class: 'w3-btn '+defbtn.color+' w3-text-'+defbtn.text_color+' w3-round w3-margin-right w3-border',
						click: defbtn.click
					}).html(defbtn.text));
				});
	
				return buttons_div;
			}).append(
				()=>{
					this.$footer=$('<div>', {
						class: 'w3-margin-top w3-padding w3-small'
					}).html(opts.footer);
					return this.$footer;
				}
			)));
	
			return main_msg_con;
		}).show(0, opts.afterShow);
	
		self.backoverlay.show().click(function(e){
			e.preventDefault();
			if(opts.easyClose){
				self.close();
			}
		});	
	}

	close(complete = null){
		var self = this;
		
		if(typeof complete !== 'function'){
			if(typeof this.$close=='function') complete = this.$close;
			else complete = function(){};
		}
		self.backoverlay.fadeOut(100, function(){
			self.backoverlay.remove();
			complete(arguments);

		}).off('click');
		self.wrapper.parent().remove();
	}
}
