<?php
include "lib/Noid.php";
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . '/noid/NoidUI.php';

?>

<html>
<head>
    <title>Test Noid</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
          integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-sm">
            <h1 class="text-center">Ark Service</h1>
        </div>
    </div>


    <div class="card">
        <?php
        if (isset($_GET['db'])) {
            print '<h5 class="card-header">Database <i>' . $_GET['db'] . '</i> is selected.</h5>';
        } else {
            print <<<EOS
                    <h5 class="card-header">Create Database</h5>
                EOS;
        }
        ?>

        <div class="card-body">
            <div id="row-dbcreate" class="row">
                <div class="col-sm-5">
                    <?php
                    if (!isset($_GET['db'])) {
                        print <<<EOS
                            <form id="form-dbcreate" action="./index.php" method="post">
                                <div class="form-group">
                                    <label for="enterDatabaseName">Database Name:</label>
                                    <input type="text" class="form-control" id="enterDatabaseName" name="enterDatabaseName"
                                           required/>
                                       <small id="emailHelp" class="form-text text-muted">It will create sub-directory under db directory for each database created. For Example: db/Test_1/NOID/.....</small>
                                </div>
                                <div class="form-group">
                                    <label for="enterDatabaseName">Prefix:</label>
                                    <input type="text" class="form-control" id="enterPrefix" name="enterPrefix"
                                           required/>
                                   <small id="emailHelp" class="form-text text-muted">For example: utsc or f5 <a target="_blank" href="https://redmine.digitalscholarship.utsc.utoronto.ca/issues/9125#note-24">(in Irfan's Note)</a> </small>
                                </div> 
                               
                                
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Template:</label>
                                    <select class="form-control" id="selectTemplate" name="selectTemplate" required>
                                        <option selected disabled value="">Choose...</option>
                                        <option>.rddd</option>
                                        <option>.sdddddd</option>
                                        <option>.zd</option>
                                        <option>.rdddd</option>
                                        <option>.sdd</option>
                                        <option>.se</option>
                                        <option>.reee</option>
                                        <option>.rdedeedd</option>
                                        <option>.zededede</option>
                                        <option>.sdede</option>
                                        <option>.rdedk</option>
                                        <option>.sdeeedk</option>
                                        <option>.zdeek</option>
                                        <option>.redek</option>
                                        <option>.reedeedk</option>
                                    </select>
                                    <small id="emailHelp" class="form-text text-muted">For more infomration <a target="_blank" href="https://redmine.digitalscholarship.utsc.utoronto.ca/issues/9125#note-24">(in Irfan's Note)</a> </small>
                                </div>
                                
                                 <div class="form-group">
                                    <label for="enterDatabaseName">Redirect URL:</label>
                                    <input type="text" class="form-control" id="enterRedirect" name="enterRedirect"
                                           required/>
                                   <small id="emailHelp" class="form-text text-muted">For example: digital.utsc.utoronto.ca <a target="_blank" href="https://redmine.digitalscholarship.utsc.utoronto.ca/issues/9125#note-24">(in Irfan's Note)</a> </small>
                                </div>
                                <div class="form-group">
                                    <label for="enterDatabaseName">Term identifier minter:</label>
                                    <select class="form-control" id="identifier_minter" name="identifier_minter" required>
                                        <option selected disabled value="">Choose...</option>
                                        <option>short</option>
                                        <option>medium</option>
                                        <option>long</option>
                                    </select>
                                    <small id="emailHelp" class="form-text text-muted">For more infomration <a target="_blank" href="https://redmine.digitalscholarship.utsc.utoronto.ca/issues/9125#note-24">(in Irfan's Note)</a> </small>
                                </div>
                                
                                 <div class="form-group">
                                    <label for="enterDatabaseName">Insitution Name:</label>
                                    <input type="text" class="form-control" id="enterInsitutionName" name="enterInsitutionName"
                                           required/>
                                   <small id="emailHelp" class="form-text text-muted">For example: dsu/utsc-library or oac/cmp <a target="_blank" href="https://redmine.digitalscholarship.utsc.utoronto.ca/issues/9125#note-24">(in Irfan's Note)</a> </small>
                                </div>
                                
                                <input type="submit" name="dbcreate" value="Create" class="btn btn-primary"/>
                            </form>
                            EOS;

                    } else {
                        print <<<EOS
                            <a class="btn btn-secondary" href="./index.php">Reset</a>
                        EOS;
                    }
                    ?>

                </div>
                <div class="col-sm-7">
                    <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['dbcreate']) && !isset($_GET['db'])) {
                        // Repace white space (if there is) with underscore
                        $database = str_replace(" ", "_", $_POST['enterDatabaseName']);

                        // generate an ark service processing object
                        $noidUI = new NoidUI();

                        // check parent database folder created yet, if not, create it.
                        if (!file_exists($noidUI->path($database))) {
                            mkdir($noidUI->path($database), 0775);
                        }

                        // Execute dbcomment with entered parameters.
                        $result = $noidUI->exec_command("dbcreate " . $_POST['enterPrefix'] . $_POST['selectTemplate'] . " " . $_POST['identifier_minter'] . " 61220 " . $_POST['enterRedirect'] . " " . $_POST['enterInsitutionName'], $noidUI->path($database));

                        // check new database directory created yet. if yes, display success message, if not display error message.
                        $isReadable = file_exists($noidUI->path($database) . '/NOID/README');
                        if ($isReadable) {
                            $result = <<<EOS
                                <div class="alert alert-success" role="alert">
                                    New database <i>$database</i> created successfully.
                                </div>
                            EOS;
                            print $result;
                        }
                        else {
                            $result = <<<EOS
                                <div class="alert alert-danger" role="alert">
                                    Sorry, failed to create <i>$database</i>.
                                </div>
                            EOS;
                            print $result;
                        }
                        header("Location: index.php");
                    }

                    // List all created databases in the table
                    $dirs = scandir(NoidUI::dbpath());
                    if (is_array($dirs) && count($dirs) > 2) {
                        ?>
                        <div class="row">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th scope="col">Past database</th>
                                    <th scope="col">Select</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php


                                foreach ($dirs as $dir) {
                                    $highlight = "";
                                    $setActive = '<a href="./index.php?db=' . $dir . '">Select</a>';
                                    if (!in_array($dir, ['.', '..'])) {
                                        if ((isset($_GET['db']) && $_GET['db'] == $dir)) {
                                            $setActive = "<strong>Selected</srong>";
                                            $highlight = 'class="table-success"';
                                        }
                                        print <<<EOS
                                        <tr $highlight>
                                            <td scope="row">$dir</td>
                                            <td scope="row">$setActive</td>
                                        </tr>
                                    EOS;
                                    }
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>

                </div>

            </div>
        </div>
    </div>

    <?php
    if (isset($_GET['db'])) { // if a database is selected (db name appears in the URL
        ?>
        <hr>
        <div class="card">
            <h5 class="card-header">Minting</h5>
            <div class="card-body">
                <div id="row-mint" class="row">
                    <div class="col-sm-5">
                        <form id="form-mint" method="post" action="./index.php?db=<?php echo $_GET['db'] ?>">
                            <div class="form-group">
                                <input type="hidden" name="db" value="<?php echo $_GET['db'] ?>">
                                <label for="exampleInputEmail1">How many:</label>
                                <input type="number" class="form-control" id="mint-number" name="mint-number">
                            </div>
                            <input type="submit" name="mint" value="Mint" class="btn btn-primary"/>
                        </form>
                    </div>
                    <div class="col-sm-7">
                        <?php
                        if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['mint-number'])) {
                            // create Ark service procession object
                            $noidUI = new NoidUI();

                            // execute command with entered params
                            $result = $noidUI->exec_command("mint " . $_POST['mint-number'], $noidUI->path($_GET["db"]));

                            // remove any empty identifer happen to be in the result array
                            $newIDs = array_filter(explode("id: ", $result));

                            // Create an CSV and write minted identifiers to that new CSV
                            $noidUI->toCSV($noidUI->path($_GET["db"]), $newIDs, time());

                            // redirect to the page.
                            header("Location: index.php?db=" . $_GET["db"]);
                        }

                        // List all minted identifer in csv which created each time execute mint
                        $dirs = scandir(NoidUI::dbpath() . $_GET['db'] . '/mint');
                        if (count($dirs) > 2) {
                            ?>
                            <div class="row">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th scope="col">Past minting</th>
                                        <th scope="col">Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($dirs as $dir) {
                                        if (!in_array($dir, ['.', '..'])) {
                                            $csv = (isset($_GET['db']) && $_GET['db'] == $dir) ? 'Currently Active' : '<a href="' . '/noid/db/' . $_GET['db'] . '/mint/' . $dir . '">' . $dir . '</a>';
                                            $date = date("F j, Y, g:i a", explode('.', $dir)[0]);

                                            print <<<EOS
                                        <tr>
                                            <td scope="row">$csv</td>
                                            <td scope="row">$date</td>
                                        </tr>
                                    EOS;
                                        }
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php } ?>


                    </div>
                </div>
            </div>
        </div>

        <hr>
        <div class="card">
            <h5 class="card-header">Bind Set</h5>
            <div class="card-body">
                <div id="row-bindset" class="row">
                    <div class="col-sm-4">
                        <form id="form-bindset" method="post" action="./index.php?db=<?php echo $_GET['db'] ?>">
                            <div class="form-group">
                                <label for="enterIdentifier">Identifier:</label>
                                <input type="text" class="form-control" id="enterIdentifier" name="enterIdentifier"
                                       required>
                            </div>
                            <div class="form-group">
                                <label for="enterKey">Key:</label>
                                <input type="text" class="form-control" id="enterKey" name="enterKey" required>
                            </div>
                            <div class="form-group">
                                <label for="enterValue">Value:</label>
                                <input type="text" class="form-control" id="enterValue" name="enterValue" required>
                            </div>
                            <input type="submit" name="bindset" value="Bind" class="btn btn-primary"/>
                        </form>
                    </div>
                    <div class="col-sm-8">
                        <?php
                        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['bindset']) && !empty($_POST['enterIdentifier'])) {
                            // create Ark service procession object
                            $noidUI = new NoidUI();

                            // Execute command with entered params
                            $result = $noidUI->exec_command(" bind set " . $_POST['enterIdentifier'] . " " . $_POST['enterKey'] . " '" . $_POST['enterValue'] . "'", $noidUI->path($_GET["db"]));

                            // display the rsults
                            print('<div class="alert alert-info">');
                            print("<p><strong>Result:</strong></p>");
                            print($result);
                            print("</div>");
                            // refresh the page to clear Post method.
                            header("Location: index.php?db=" . $_GET["db"]);
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>


        <hr>
        <div class="card">
            <h5 class="card-header">Fetch</h5>
            <div class="card-body">

                <div id="row-fetch" class="row">
                    <div class="col-sm-3">
                        <form id="form-fetch" method="post" action="./index.php?db=<?php echo $_GET['db'] ?>">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Identifer:</label>
                                <input type="text" class="form-control" id="identifer" name="identifer">
                            </div>
                            <input type="submit" name="fetch" value="Fetch" class="btn btn-primary"/>
                        </form>
                    </div>
                    <div class="col-sm-9">

                        <p>
                            <?php

                            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['fetch'])) {
                                // creat an ark service processor
                                $noidUI = new NoidUI();

                                // execute the command with entered params
                                $result = $noidUI->exec_command("fetch " . $_POST['identifer'], $noidUI->path($_GET["db"]));

                                // display the result
                                print('<div class="alert alert-info">');
                                print "<p><strong>Result:</strong></p>";
                                print($result);
                                print('</div>');

                                // refresh the page to destroy post section
                                header("Location: index.php?db=" . $_GET["db"]);
                            }
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <hr>
        <div class="card">
            <h5 class="card-header">Get</h5>
            <div class="card-body">

                <div id="row-fetch" class="row">
                    <div class="col-sm-3">
                        <form id="form-get" method="post" action="./index.php?db=<?php echo $_GET['db'] ?>">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Identifer:</label>
                                <input type="text" class="form-control" id="identifer" name="identifer">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Key:</label>
                                <input type="text" class="form-control" id="key" name="enterkey">
                            </div>
                            <input type="submit" name="get" value="Get" class="btn btn-primary"/>
                        </form>
                    </div>
                    <div class="col-sm-9">

                        <p>
                            <?php
                            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['get'])) {
                                // generate Ark service procession object
                                $noidUI = new NoidUI();

                                // execute with entered params
                                $result = $noidUI->exec_command("get " . $_POST['identifer'] . ' ' . $_POST['enterKey'], $noidUI->path($_GET["db"]));

                                // display the results
                                print('<div class="alert alert-info">');
                                print "<p></p><strong>Result</strong></p>";
                                print($result);
                                print('</div>');

                                // refresh the page to destroy post session
                                header("Location: index.php?db=" . $_GET["db"]);
                            }
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>


        <hr>
        <div class="card">
            <h5 class="card-header">Import with minted identifiers</h5>
            <div class="card-body">
                <div id="row-search" class="row">
                    <div class="col-sm-4">
                        <form id="form-import" method="post" enctype="multipart/form-data"
                              action="./index.php?db=<?php echo $_GET['db'] ?>">
                            <div class="form-group">
                                <p><label for="importCSV">Upload an CSV: </label></p>
                                <input type="file"
                                       id="importCSV" name="importCSV"
                                       accept=".csv">
                                <small id="emailHelp" class="form-text text-muted">Only accept CSV</small>
                                <p><strong><u>Note:</u></strong> For this section, download minted identifiers above add
                                    fields as column, and make sure to export as pure CSV, the import it here.</p>
                            </div>
                            <input type="submit" name="import" value="Import" class="btn btn-primary"/>
                        </form>
                    </div>
                    <div class="col-sm-8">
                        <?php
                        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['import'])) {

                            if (is_uploaded_file($_FILES['importCSV']['tmp_name'])) {
                                // generate Ark service procession object
                                $noidUI = new NoidUI();

                                //Read and scan through imported csv
                                if (($handle = fopen($_FILES['importCSV']['tmp_name'], "r")) !== FALSE) {
                                    $columns = fgetcsv($handle, 0, ",");

                                    $flag = true;
                                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                                        // avoid the 1st row since it's header
                                        if ($flag) {
                                            $flag = false;
                                            continue;
                                        }
                                        $num = count($data);
                                        $row++;

                                        $identifier = null;
                                        for ($c = 0; $c < $num; $c++) {

                                            // capture identifier (strictly recommend first column)
                                            if ($columns[$c] === 'Identifer') {
                                                $identifier = $data[$c];
                                            }
                                            if ($c >0) { // avoid bindset identifier column
                                                // mapping each column as params
                                                $bindset_cmd = " bind set " . $identifier;
                                                $bindset_cmd .= " " . $columns[$c] . " '" . $data[$c] . "'";;
                                                $result = $noidUI->exec_command($bindset_cmd, $noidUI->path($_GET["db"]));

                                                // display result of each bindset
                                                print('<div class="alert alert-info">');
                                                print "<p></p><strong>Result</strong></p>";
                                                print($result);
                                                print('</div>');
                                            }
                                            //TODO: write each row to new csv
                                        }

                                    }
                                    fclose($handle);
                                }
                            }
                            header("Location: index.php?db=" . $_GET["db"]);
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <hr>
        <div class="card">
            <h5 class="card-header">Import <u>without</u> minted identifiers</h5>
            <div class="card-body">
                <div id="row-search" class="row">
                    <div class="col-sm-4">
                        <form id="form-import" method="post" enctype="multipart/form-data"
                              action="./index.php?db=<?php echo $_GET['db'] ?>">
                            <div class="form-group">
                                <p><label for="importCSV_noID">Upload an CSV: </label></p>
                                <input type="file"
                                       id="importCSV_noID" name="importCSV_noID"
                                       accept=".csv">
                                <small id="emailHelp" class="form-text text-muted">Only accept CSV</small>
                                <p><strong><u>Note:</u></strong> For this section, please generate columns without need
                                    of mint before, it will mint during the import the CSV.</p>
                            </div>
                            <input type="submit" name="import_noID" value="Import" class="btn btn-primary"/>
                        </form>
                    </div>
                    <div class="col-sm-8">
                        <?php
                        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['import_noID'])) {

                            if (is_uploaded_file($_FILES['importCSV_noID']['tmp_name'])) {
                                $noidUI = new NoidUI();
                                if (($handle = fopen($_FILES['importCSV_noID']['tmp_name'], "r")) !== FALSE) {
                                    $columns = fgetcsv($handle, 0, ",");

                                    $flag = true;
                                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                                        if ($flag) {
                                            $flag = false;
                                            continue;
                                        }
                                        $num = count($data);
                                        $row++;
                                        $identifier = $noidUI->mint;
                                        for ($c = 0; $c < $num; $c++) {


                                            $bindset_cmd = " bind set " . $identifier;
                                            $bindset_cmd .= " " . $columns[$c] . " '" . $data[$c] . "'";;
                                            $result = $noidUI->exec_command($bindset_cmd, $noidUI->path($_GET["db"]));
                                            print($result);
                                        }

                                    }
                                    fclose($handle);
                                }
                            }
                            header("Location: index.php?db=" . $_GET["db"]);
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    <?php }
    ?>
</div>
</body>
</html>