<?php 
require_once "class/config.php";
$title = "Reviews";
require_once "include/header.php" ?>
        <div class="col-12 col-sm-8 col-md-6 mx-auto mt-3">
            <div class="h2 mt-2">Write a review</div>
            <form id="review-form" method="post">
                <div class="form-group">
                    <label for="fullname">Fullname</label>
                    <input name="fullname" type="text" class="form-control" placeholder="Enter your name..." id="fullname">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input name="email" type="email" class="form-control" placeholder="Enter your email..." id="email">
                </div>
                <div class="form-group">
                    <label for="rating">Rating</label>
                    <select name="rating" id="rating" class="form-control">
                        <option value="">Select rating</option>
                        <option>5</option>
                        <option>4</option>
                        <option>3</option>
                        <option>2</option>
                        <option>1</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="review">Review</label>
                    <textarea name="review" id="review" placeholder="Enter review...." rows="10" cols="10" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <div class="review-info"></div>
                    <button class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>
        <style>
            .row {
                margin-left: 0 !important;
            }
        </style>





<?php require_once "include/footer.php" ?>