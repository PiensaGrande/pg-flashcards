<?php namespace pg_flashcards; ?>
<?php
global $lang1;

// default english // flashcards
$templ["goto_admin"] = "Goto Admin";
$templ["disk_space_avail"] = "Available disk space";
$templ["card_upload"] = "Create Cards";
$templ["no_space_for_new"] = "Insufficient disk space to upload cards.";
$templ["just_image"] = "Image";
$templ["just_title"] = "Title";
$templ["just_description"] = "Description";
$templ["large_file"] = "is too large";
$templ["fix_errors"] = "Please fix the following errors";
$templ["required"] = "is required";
$templ["special_chars"] = "Please eliminate the special characters";
$templ["allowed_chars_title"] = "Only alphamumeric characters, underscores, and dashes are allowed in the title";
$templ["no_upload_space"] = "There is not sufficient disk space to upload this image";
$templ["upload_your_content"] = "Upload your content!";
$templ["file_to_upload"] = "File to upload";
$templ["select"] = "Select";
$templ["content_type"] = "Content type";
$templ["upload"] = "Upload";
$templ["delete"] = "Delete";
$templ["error"] = "Error";
$templ["preview"] = "Preview";
$templ["save"] = "Save";
$templ["new"] = "New";
$templ["optional"] = "Optional";
$templ["finished"] = "Finished";
$templ["saved"] = "Saved!";
$templ["are_you_sure"] = "Are you sure?";
$templ["delete_element"] = "Delete Element";

// override with language translations when available
// This allows for partial translations to exists
switch ($lang1) {
	case ("es"):
		$templ["goto_admin"] = "Ir a Admin";
		$templ["disk_space_avail"] = "Espacio disponible en el disco";
		$templ["card_upload"] = "Crear Tarjetas";
		$templ["no_space_for_new"] = "Insuficiente espacio en disco para cargar tarjetas.";
		$templ["fix_errors"] = "Arreglar los siguientes errores";
		$templ["required"] = "es requerido";
		$templ["special_chars"] = "Por favor, elimina los caracteres especiales"; 
		$templ["allowed_chars_title"] = "Sólo caracteres alfanuméricos, _ y - están permitidos en el Título";
		$templ["no_upload_space"] = "No hay suficiente espacio en el disco para cargar";
		$templ["upload_your_content"] = "¡Carga su contenido!";
		$templ["file_to_upload"] = "Archivo para cargar";
		$templ["select"] = "Seleccione";
		$templ["content_type"] = "Tipo de contenido";
		$templ["upload"] = "Cargar";
		$templ["delete"] = "Borrar";
		$templ["error"] = "Error";
		$templ["preview"] = "Preestreno";
		$templ["save"] = "Guardar";
		$templ["new"] = "Nuevo";
		$templ["optional"] = "Opcional";
		$templ["finished"] = "Acabado";
		$templ["saved"] = "¡Se guardó!";
		$templ["are_you_sure"] = "¿Seguro?";
		$templ["title_uri_required"] = "Título y Enlace necesitan valores";
		$templ["delete_element"] = "Borrar Elemento";
		break;
	// can support additional languages via extra cases 
}

?>
