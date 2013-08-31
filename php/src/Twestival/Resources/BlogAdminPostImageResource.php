<?php namespace Twestival\Resources;

/**
 * @namespace blog
 * @uri /admin/post/image
 */
class BlogAdminPostImageResource extends BaseBlogResource
{
	/**
	 * @method post
	 * @provides text/html
	 * @requireCurrentBlogEventAdmin
	 */
	function upload()
	{
		$upload = new Files\BlogPostUpload($this->container, 'upload');
		$files = $upload->process($_FILES);
	
		return $this->renderMustacheHeaderFooter('Blog/Admin/Post/Image', array(
				'File' => $files['upload'],
				'Callback' => $_GET['CKEditorFuncNum']
		));
	}
}
?>