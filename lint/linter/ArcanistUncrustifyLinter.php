<?php

/**
 * Uses the clang format to format C/C++/Obj-C code
 */
final class ArcanistUncrustifyLinter extends ArcanistExternalLinter {

  public function getInfoName() {
    return 'Uncrustify';
  }

  public function getInfoURI() {
    return 'http://uncrustify.sourceforge.net/';
  }

  public function getInfoDescription() {
    return pht(
      'A tool to format C/C++/Java/Objective-C/Embedded SQL code.');
  }

  public function getLinterName() {
    return 'Uncrustify';
  }

  public function getLinterConfigurationName() {
    return 'Uncrustify';
  }

  public function getLinterConfigurationOptions() {
    $options = array(
    );

    return $options + parent::getLinterConfigurationOptions();
  }

  public function getDefaultBinary() {
    return 'uncrustify';
  }

  public function getVersion() {
    /* Copied parsing code from 
     * https://github.com/pwithnall/morefas-phabricator/blob/master/lint/linter/FnClangFormatLinter.php */
    list($stdout) = execx('%C --version', $this->getExecutableCommand());

    $matches = array();
    $regex = '/^uncrustify (?P<version>[0-9]*\.*?[0-9]+)/';
    if (preg_match($regex, $stdout, $matches)) {
      return $matches['version'];
    } else {
      return false;
    }
  }

  public function getInstallInstructions() {
    return pht('Make sure uncrustify is in directory specified by $PATH');
  }

  public function shouldExpectCommandErrors() {
    return false;
  }

  protected function getMandatoryFlags() {
    return array(
    );
  }

  protected function parseLinterOutput($path, $err, $stdout, $stderr) {
    $ok = ($err == 0);

    if (!$ok) {
      return false;
    }

    $root = $this->getProjectRoot();
    $path = Filesystem::resolvePath($path, $root);
    $orig = file_get_contents($path);
    if ($orig == $stdout) {
      return array();
    }

    $message = id(new ArcanistLintMessage())
      ->setPath($path)
      ->setLine(1)
      ->setChar(1)
      ->setGranularity(ArcanistLinter::GRANULARITY_FILE)
      ->setCode('CFMT')
      ->setSeverity(ArcanistLintSeverity::SEVERITY_AUTOFIX)
      ->setName('Code style violation')
      ->setDescription("'$path' has code style errors.")
      ->setOriginalText($orig)
      ->setReplacementText($stdout);
    return array($message);
  }
}
