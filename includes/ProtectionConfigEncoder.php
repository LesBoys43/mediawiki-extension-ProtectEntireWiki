class ProtectionConfigEncoder {
	public function __construct() {
		throw new BadMethodCallException("This is a static-only-class");
	}
}