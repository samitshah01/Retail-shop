<?php
$active = "Product";
include("db.php");
include("functions.php");
include('header.php');
?>
<div style="overflow: hidden;">
    <!-- Breadcrumb Section Begin -->
    <div class="breacrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-text product-more">
                        <a href="index.php"><i class="fa fa-home"></i> Home</a>
                        <span>Blog</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Section Begin -->

</div>


<div class="related-products spad">
    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-8">
                <h3>Welcome to our Fashion Blog!</h3>
                <div class="card mb-4">
                    <img src="img/blog/blog1.jpg" class="card-img-top" alt="Blog Image 1">
                    <div class="card-body">
                        <h5 class="card-title">Leandra Medine On The Importance Of Maintaining Her Personal Style</h5>
                        <p class="card-text">The founder of Man Repeller is already thinking about outfitting you for New Year's Eve 2020. Leandra Medine designed a limited-edition capsule collection for Mango, translating her own personal, eclectic style into an accessible and shoppable line. </p>
                        <a href="#" class="btn btn-primary">Read More</a>
                    </div>
                </div>
                <div class="card mb-4">
                    <img src="img/blog/blog2.jpg" class="card-img-top" alt="Blog Image 1">
                    <div class="card-body">
                        <h5 class="card-title">Olivia Anthony Thinks There Absolutely Should Be Crying In Fashion</h5>
                        <p class="card-text">Introducing Self-Made, Refinery29's newest column spotlighting the real stories that fueled success — the wins, the fails, and the curveballs —proving there's no one path to getting what you want. </p>
                        <a href="#" class="btn btn-primary">Read More</a>
                    </div>
                </div>
                

                <!-- Add more blog posts here -->

            </div>
            

        <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Categories</h5>
                        <ul class="list-group">
                        <li class="list-group-item">Category 1</li>
                        <li class="list-group-item">Category 2</li>
                        <li class="list-group-item">Category 3</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>

<?php
include('footer.php');
?>



</body>

</html>