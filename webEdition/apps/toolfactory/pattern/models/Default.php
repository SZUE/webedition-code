/**
 * Base class for app models
 *
 * @category   app
 * @package none
 * @license    http://www.gnu.org/licenses/lgpl-3.0.html  LGPL
 */

require_once($_SERVER['DOCUMENT_ROOT'].'/webEdition/we/include/we.inc.php');

class <?php echo $CLASSNAME;?>_models_Default extends we_app_Model
{
	/**
	 * ContentType attribute
	 *
	 * @var string
	 */
	public $ContentType = "<?php echo $TOOLNAME;?>/item";

	/**
	 * _appName attribute
	 *
	 * @var string
	 */
	protected $_appName = '<?php echo $TOOLNAME;?>';

	/**
	 * Constructor
	 *
	 * @param string $<?php echo $TOOLNAME;?>ID
	 * @return void
	 */
	function __construct($<?php echo $TOOLNAME;?>ID = 0)
	{
		parent::__construct(<?php echo ($TABLEEXISTS && !empty($TABLECONSTANT)) ? $TABLECONSTANT : "''";?>);
		if ($<?php echo $TOOLNAME;?>ID) {
			$this->{$this->_primaryKey} = $<?php echo $TOOLNAME;?>ID;
			$this->load($<?php echo $TOOLNAME;?>ID);
		}
		<?php if(!isset($TABLECONSTANT) || !$TABLEEXISTS || (isset($TABLECONSTANT) && empty($TABLECONSTANT))) {?>
			$this->setPersistentSlots(array('ID', 'Text'));
		<?php } ?>

	}

	/**
	 * set Fields
	 *
	 * @param array $fields
	 * @return void
	 */
	public function setFields($fields) {
		parent::setFields($fields);
		<?php if($TABLEEXISTS && !empty($TABLECONSTANT)) {?>
			$this->setPath();
		<?php } ?>
	}

	<?php if(!isset($TABLECONSTANT) || !$TABLEEXISTS || (isset($TABLECONSTANT) && empty($TABLECONSTANT))) {?>
	/**
	 * Load entry
	 *
	 * @param integer $id
	 * @return boolean returns true on success, otherwise false
	 */
	function load($id)
	{
		return true;
	}

	/**
	 * Save entry
	 *
	 * @return boolean returns true on success, otherwise false
	 */
	function save($skipHook=0)
	{
		// allowing hook functionality
        /* hook */
		if (!$skipHook){
			$hook = new weHook('save', $this->_appName, array($this));
			$hook->executeHook();
        }

		return true;
	}
	<?php } ?>

}
