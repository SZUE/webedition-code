<?php

$this->NeedsEndTag = true;
$this->Groups = array();
$this->Module = 'customer';

$this->Attributes = array(
	new weTagData_selectorAttribute('id', FILE_TABLE, 'text/webedition', true, 'customer'),
	new weTagData_textAttribute('host', false, 'customer'),
	new weTagData_selectAttribute('plain', weTagData_selectAttribute::getTrueFalse(), false, 'customer'),
);
