<?php
namespace Tests\tripal_chado\api;

use StatonLab\TripalTestSuite\DBTransaction;
use StatonLab\TripalTestSuite\TripalTestCase;
use Faker\Factory;

module_load_include('inc', 'tripal_chado', 'api/ChadoSchema');

/**
 * Tests the ChadoSchema class.
 *
 * @todo test "Check" functions in the ChadoSchema class.
 */
class ChadoSchemaTest extends TripalTestCase {
  use DBTransaction;

  /**
   * Tests that the class can be initiated with or without a record specified
   *
   * @group api
   * @group chado
   * @group chado-schema
   * @group lacey
   */
  public function testInitClass() {

    // Test with no parameters.
    $chado_schema = new \ChadoSchema();
    $this->assertNotNull($chado_schema);

    // Test with version.
    $chado_schema = new \ChadoSchema('1.3');
    $this->assertNotNull($chado_schema);
  }

  /**
   * Tests the ChadoSchema->getVersion() method.
   *
   * @group api
   * @group chado
   * @group chado-schema
   * @group lacey
   */
  public function testGetVersion() {

    // Generate a fake version.
    $faker = Factory::create();
    $version = $faker->randomFloat(2, 1, 5);

    // Check version can be retrieved when we set it.
    $chado_schema = new \ChadoSchema($version);
    $retrieved_version = $chado_schema->getVersion();
    $this->assertEquals(
      $version,
      $retrieved_version,
      t('The version retrieved via ChadoSchema->getVersion, :ret, should equal that set, :set',
        array(':ret' => $retrieved_version, ':set' => $version))
    );

    // @todo Check version can be retrieved when it's looked up?
  }

  /**
   * Tests the ChadoSchema->getSchemaName() method.
   *
   * @group api
   * @group chado
   * @group chado-schema
   * @group lacey
   */
  public function testGetSchemaName() {

    // Generate a fake version.
    $faker = Factory::create();
    $version = $faker->randomFloat(2, 1, 5);
    $schema_name = $faker->word();

    // Check the schema name can be retrieved when we set it.
    $chado_schema = new \ChadoSchema($version, $schema_name);
    $retrieved_schema = $chado_schema->getSchemaName();
    $this->assertEquals(
      $schema_name,
      $retrieved_schema,
      t('The schema name retrieved via ChadoSchema->getSchemaName, :ret, should equal that set, :set',
        array(':ret' => $retrieved_schema, ':set' => $schema_name))
    );

    // @todo Check schema name can be retrieved when it's looked up?
  }

  /**
   * Tests ChadoSchema->getTableNames() method.
   *
   * @dataProvider knownTableProvider
   *
   * @group api
   * @group chado
   * @group chado-schema
   * @group lacey
   */
  public function testGetTableNames($version, $known_tables) {

    // Check: Known tables for a given version are returned.
    $chado_schema = new \ChadoSchema($version);
    $returned_tables = $chado_schema->getTableNames();

    foreach ($known_tables as $table_name) {
      $this->assertArrayHasKey(
        $table_name,
        $returned_tables,
        t('The table, :known, should exist in the returned tables list for version :version.',
          array(':known' => $table_name, ':version' => $version))
      );
    }
  }

  /**
   * Data Provider: returns known tables specific to a given chado version.
   *
   * @return array
   */
   public function knownTableProvider() {
    // chado version, array of 3 tables specific to version.

    return [
      ['1.2', ['cell_line_relationship', 'cvprop', 'chadoprop']],
      ['1.3', ['analysis_cvterm', 'dbprop', 'organism_pub']],
    ];
   }
}
