<!-- home.php script -->
<?php require('backend/home.php'); ?>
<!-- form -->


<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
    <title>HTMl TO PDF CONVERSION</title>
    <link href="../assets/css/style.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" >
    
</head>

<body>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h2 class="card-title m-0"> <img src="/assets/images/logo.png"></img></h2>
            <div>
            <?php
            if ($_SESSION['is_admin'] == '1') {
            ?>
                <a href="/register" class="btn btn-block btn-outline-info">Créer un user </a>
                &nbsp;

            <?php } ?>
            <a href="/password_reset" class="btn btn-block btn-outline-warning">Changer de mot de passe</a>
            &nbsp;
            <a href="/logout" class="btn btn-block btn-outline-danger">Déconnexion</a>

            </div>
        </div>
        <div class="card-body">
            <h6 class="display-8">Bienvenue <?php echo $_SESSION['username']; ?></h6>
        </div>
        <?php
            if ($_SESSION['is_admin'] == '1') {           
        ?>
        <h9><small><p class="text-end">License: <?php echo $_SESSION['license']; ?></p></small></h9>
        <?php } ?>
    </div>

    <div class="container">
        <div class="text-center">
            <h2>HTML2PDF</h2>
            <br />
        </div>


        <form action="" method="post">
            <div class="row">
                <br />
                <h5>Conversion HTML en PDF et Paramètrage.</h5><br /><br />
                <h8>Vous pouvez utiliser cette page pour effectuer des conversions manuelles ou pour enregistrer vos paramètres par défaut.</h8>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" type="text" name="lien" id="floatingInput" />
                    <!-- Error -->
                    <?php echo $lienEmptyErr; ?>
                    <div class="input-group-append">
                        <div class="col-12">
                            <button class="btn btn-primary" type="conversion" name="conversion">Convertir</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>


        <br />
        <div class="text-center">
            <h8>Enregistrer les paramètres de conversion de vos page HTML. <a href="#">Contactez nous</a> si vous rencontrez des soucis de conversion.</h8>
            <br /><br />
        </div>
        <?php //var_dump($parametres); ?>
    
        <!-- Début Formulaire d'enregistrement des paramètres -->
        <form action="" method="post">

            <div class="row">
                <span class="border border-2">

                    <span>
                        Réglages de la page
                    </span>
                    <br /><br />
                    <div class="col">

                        <div class="row mb-3 form-group">
                            <label for="" class="col-sm-3 col-form-label">Taille</label>
                            <div class="col-sm-8">
                                <select class="form-select <?php (!empty($taille_err))?'has_error':'';?>" id="taille" name="taille" aria-label="Default select example">
                                    <option selected="" disabled>-- Selectionner l'orientation --</option>
                                    <option value="Letter">Letter [8.5 x 11.0 in]</option>
                                    <option value="Legal">Legal [8.5 x 14.0 in]</option>
                                    <option value="Tabloid">Tabloid [11 x 17 in]</option>
                                    <option value="Ledger">Ledger [17 x 11 in]</option>
                                    <option value="A0">A0 [841 x 1189 mm, 33.1 x 46.8 in]</option>
                                    <option value="A1">A1 [594 x 841 mm, 23.4 x 33.1 in]</option>
                                    <option value="A2">A2 [420 x 594 mm, 16.5 x 23.4 in] </option>
                                    <option value="A3">A3 [297 x 420 mm, 11.7 x 16.5 in]</option>
                                    <option value="A4">A4 [210 x 297 mm, 8.3 x 11.7 in] </option>
                                    <option value="A5">A5 [148 x 210 mm, 5.8 x 8.3 in]</option>
                                    <option value="A6">A6 [105 x 148 mm, 4.1 x 5.8 in]</option>
                                </select>
                                <span class="help-block error"><?php echo $taille_err;?></span>
                            </div>
                        </div>

                        <br />

                        <fieldset class="row mb-3">
                            <legend class="col-form-label col-sm-3 pt-0">Orientation</legend>
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <input class="form-check-input" type="radio" name="orientation" id="Portrait" value="Portrait" checked>
                                    <label class="form-check-label" for="portrait">
                                        Portrait
                                    </label>
                                    &nbsp;&nbsp;&nbsp;
                                    <input class="form-check-input" type="radio" name="orientation" id="Landscape" value="Landscape">
                                    <label class="form-check-label" for="Landscape">
                                        Paysage
                                    </label>
                                </div>
                            </div>
                        </fieldset>

                        <br />

                        <div class=" form-group row mb-6">
                            <label for="" class="col-sm-3 col-form-label">Margin</label>
                            <!-- Margin TOP -->
                            <div class="form-group col-sm-2">
                                <input type="text" name="margin_top" class="form-control" placeholder="Top" aria-label="Top">
                            </div>

                            <!-- Margin Bottom -->
                            <div class="col-sm-2">
                                <input type="text" name="margin_bottom" class="form-control" placeholder="Bottom" aria-label="Bottom">
                            </div>


                            <!-- Margin Left -->
                            <div class="col-sm-2">
                                <input type="text" name="margin_left" class="form-control" placeholder="Left" aria-label="Left">
                            </div>


                            <!-- Margin Right -->
                            <div class="col-sm-2">
                                <input type="text" name="margin_right" class="form-control" placeholder="Right" aria-label="Right">
                            </div>

                        </div>
                        <br />
                        <!-- ZOOM level -->
                        <div class="row mb-3">

                            <label for="" class="col-sm-3 col-form-label">Zoom Level</label>

                            <div class="col-sm-2">
                                <input type="text" name="zoom" class="form-control" placeholder="Zoom" aria-label="Zoom">
                            </div>
                            <div class="col-sm-2">
                                <label class="col-form-label">%</label>
                            </div>
                            <br /><br /><br />
                            <div class="row mb-3">
                                <div class="col-form-label col-sm-3 pt-0">Numeroté Page</div>
                                <div class="col-sm-8">
                                    <div class="custom-control custom-checkbox custom-control-inline">
                                        <input type="checkbox" name="footer_num_page" value="[page]/[topage]" class="custom-control-input" id="footer_num_page">
                                        <label class="custom-control-label" for="footer_num_page"></label>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <br />
                        <h10>
                            Si vous voulez customiser le footer et le hearder merci de remplir les margins par défaut c'est 10, équivalent à 10mm. Pas la peine de sélectionner la taille dans ce cas.
                        </h10>
                        <br /><br />
                        <div class="row row-cols-2">
                             <!-- Header -->
                            <div class="col">
                                Header
                            </div>
                            <!-- Footer -->
                            <div class="col">
                                Footer
                            </div>
                            <div class="col">
                                <textarea class="form-control" name="headerHtml" id="headerHtml" rows="5"></textarea>
                            </div>
                            <div class="col">
                                <textarea class="form-control" name="footerHtml" id="footerHtml" rows="5"></textarea>
                            </div>
                        </div>
                        <br /><br />
                        <!-- Bouton Enregistrer les paramètres -->
                        <div class="row form-group  mb-3">
                            <div class="col-md-12 text-center">
                                <button type="submit" name="submit" class="btn btn-primary btn-block">Enregistrer les paramètres</button>
                            </div>
                        </div>
                    </div>
                </span>
            </div>
        </form>
        <br /><br />
         <!-- Table Historique conversion -->
        <div class="row">
            <h4>Historique conversion</h4><br /><br />

            <table class="table table-bordered">
                <thead class="table-primary">
                    <tr>
                        <th scope="col">Date</th>
                        <th scope="col">URL</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($historiques as $historique) : ?>
                        <tr>
                            <th scope="row"> <?php echo $historique['created']  ?></th>
                            <td><a href="<?php echo  $historique['lien']  ?>"><?php echo  $historique['lien']  ?></a> </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </div>

    <div class="container">
        <br />
        Copyright ©Net Profil
    </div>
</body>

</html>