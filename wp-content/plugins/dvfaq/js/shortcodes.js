(function() {
	tinymce.PluginManager.add('dvfaq_mce_button', function( editor, url ) {
		editor.addButton( 'dvfaq_mce_button', {
			text: 'FAQ Shortcodes',
			icon: false,
			type: 'menubutton',
					menu: [
                        {
							text: 'FAQ Category',
							onclick: function() {
								editor.windowManager.open( {
									title: 'Insert the Shortcode',
									body: [
                                        {
											type: 'textbox',
											name: 'categoryid',
											label: 'The ID of the category:',
											value: ''
										},
                                        {
											type: 'listbox',
											name: 'topicmenu',
											label: 'Topics:',
											'values': [
												{text: 'Left', value: 'left'},
												{text: 'Right', value: 'right'},
                                                {text: 'Top', value: 'top'},
                                                {text: 'None', value: 'none'}
											]
										},
                                        {
											type: 'listbox',
											name: 'searchbox',
											label: 'Search Box:',
											'values': [
												{text: 'Yes', value: 'yes'},
												{text: 'No', value: 'no'}
											]
										},
                                        {
											type: 'listbox',
											name: 'skin',
											label: 'Skin:',
											'values': [
												{text: 'Custom', value: 'custom'},
												{text: 'Light', value: 'light'},
                                                {text: 'Dark', value: 'dark'}
											]
										},
                                        {
											type: 'textbox',
											name: 'topictitle',
											label: 'Topic Title:',
											value: 'Topics'
										},
                                        {
											type: 'listbox',
											name: 'switcher',
											label: 'Open/Close All Switcher:',
											'values': [
												{text: 'Yes', value: 'yes'},
                                                {text: 'No', value: 'no'}
											]
										}
									],
									onsubmit: function( e ) {
										if(isNaN(e.data.categoryid)) {
                                            editor.windowManager.alert('Category ID must be a number.');
                                            return false;
                                        }
                                        else {
										  editor.insertContent( '[dvfaq categoryid="'+ e.data.categoryid +'" topicmenu="'+ e.data.topicmenu +'" searchbox="'+ e.data.searchbox +'" skin="'+ e.data.skin +'" topictitle="'+ e.data.topictitle +'" switcher="'+ e.data.switcher +'"]');
                                        }
									}
								});
							}
						},
                        {
							text: 'FAQ Topic',
							onclick: function() {
								editor.windowManager.open( {
									title: 'Insert the Shortcode',
									body: [
                                        {
											type: 'textbox',
											name: 'title',
											label: 'Title:',
											value: ''
										},
                                        {
											type: 'textbox',
											name: 'topicid',
											label: 'The ID of the topic:',
											value: 1
										},
                                        {
											type: 'listbox',
											name: 'skin',
											label: 'Skin:',
											'values': [
												{text: 'Custom', value: 'custom'},
												{text: 'Light', value: 'light'},
                                                {text: 'Dark', value: 'dark'}
											]
										},
                                        {
											type: 'listbox',
											name: 'searchbox',
											label: 'Search Box:',
											'values': [
												{text: 'Yes', value: 'yes'},
												{text: 'No', value: 'no'}
											]
										},
                                        {
											type: 'listbox',
											name: 'switcher',
											label: 'Open/Close All Switcher:',
											'values': [
												{text: 'Yes', value: 'yes'},
                                                {text: 'No', value: 'no'}
											]
										},
                                        {
											type: 'textbox',
											name: 'paginate',
											label: 'Max. number of the questions',
											value: ''
										},
                                        {
											type: 'listbox',
											name: 'order',
											label: 'Order:',
											'values': [
												{text: 'ASC', value: 'ASC'},
                                                {text: 'DESC', value: 'DESC'}
											]
										},
                                        {
											type: 'listbox',
											name: 'orderby',
											label: 'Order By:',
											'values': [
												{text: 'Date', value: 'date'},
                                                {text: 'ID', value: 'ID'},
                                                {text: 'Title', value: 'title'},
                                                {text: 'Random', value: 'rand'},
                                                {text: 'Comment Count', value: 'comment_count'}
											]
										}
									],
									onsubmit: function( e ) {
										if(isNaN(e.data.topicid)) {
                                            editor.windowManager.alert('Topic ID must be a number.');
                                            return false;
                                        } else if(isNaN(e.data.paginate)) {
                                            editor.windowManager.alert('Max. number of the questions must be a number.');
                                            return false;
                                        }
                                        else {
										  editor.insertContent( '[dvfaqtopic title="'+ e.data.title +'" topicid="'+ e.data.topicid +'" skin="'+ e.data.skin +'" searchbox="'+ e.data.searchbox +'" switcher="'+ e.data.switcher +'" paginate="'+ e.data.paginate +'" order="'+ e.data.order +'" orderby="'+ e.data.orderby +'"]');
                                        }
									}
								});
							}
						},
                        {
							text: 'Single FAQ',
							onclick: function() {
								editor.windowManager.open( {
									title: 'Insert the shortcode',
									body: [
                                        {
											type: 'listbox',
											name: 'headinglevel',
											label: 'Heading Level:',
											'values': [
                                                {text: 'H1', value: 'h1'},
                                                {text: 'H2', value: 'h2'},
                                                {text: 'H3', value: 'h3'},
												{text: 'H4', value: 'h4'},
                                                {text: 'H5', value: 'h5'},
                                                {text: 'H6', value: 'h6'}
											]
										},
                                        {
											type: 'textbox',
											name: 'postid',
											label: 'The ID of the question:',
											value: 1
										},
                                        {
											type: 'listbox',
											name: 'skin',
											label: 'Skin:',
											'values': [
												{text: 'Custom', value: 'custom'},
												{text: 'Light', value: 'light'},
                                                {text: 'Dark', value: 'dark'}
											]
										},
									],
									onsubmit: function( e ) {
                                        if(isNaN(e.data.postid)) {
                                            editor.windowManager.alert('The ID must be a number.');
                                            return false;
                                        }
                                        else {
										  editor.insertContent( '[dvfaqsingle headinglevel="'+ e.data.headinglevel +'" postid="'+ e.data.postid +'" skin="'+ e.data.skin +'"]');
                                        }
									}
								});
							}
						}
                    ]
		});
	});
})();