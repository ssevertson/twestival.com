CKEDITOR.editorConfig = function( config ) {
	config.toolbarGroups = [
	    { name: 'styles' },
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'align' },
		{ name: 'paragraph',   groups: [ 'list', 'blocks' ] },
		{ name: 'colors' },
		{ name: 'bidi' },
		{ name: 'insert',      groups: [ 'links', 'others', 'insert' ]}
	];
	config.removeButtons = 'Underline,Subscript,Superscript,Anchor,JustifyBlock,BGColor';
	
	config.colorButton_colors = '52C5D7,ED000C,F9A51A,72BE44,F26F21,8D53A0';
	config.forcePasteAsPlainText = true;
};
