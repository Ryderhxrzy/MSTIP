<!DOCTYPE html>
<html lang="en">
<head>
    <title>Home - MSTIP Job Search</title>
  <!-- Basic -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Mobile Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <!-- Site Metas -->
  <meta name="keywords" content="MSTIP, graduate, job search, Philippines, employment" />
  <meta name="description" content="MSTIP Graduate Job Search platform connecting graduates with employment opportunities" />
  <meta name="author" content="MSTIP Team" />
  <title>MSTIP Graduate Job Search</title>
  <!-- bootstrap core css -->
  <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.css" />
  <!-- fonts style -->
  <link href="https://fonts.googleapis.com/css?family=Poppins:400,600,700&display=swap" rel="stylesheet">
  <!-- font awesome style -->
  <link href="../assets/css/font-awesome.min.css" rel="stylesheet" />
  <!-- nice select -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css" integrity="sha256-mLBIhmBvigTFWPSCtvdu6a76T+3Xyt+K571hupeFLg4=" crossorigin="anonymous" />
  <!-- Custom styles for this template -->
  <link href="../assets/css/style.css" rel="stylesheet" />
  <!-- responsive style -->
  <link href="../assets/css/responsive.css" rel="stylesheet" />
</head>

<body>

  <div class="hero_area">
    <?php include '../includes/user_header.php'; ?>
    <!-- end header section -->
    <!-- slider section -->
    <section class="slider_section">
      <div class="container">
        <div class="row">
          <div class="col-lg-7 col-md-8 mx-auto">
            <div class="detail-box">
              <h1>MSTIP Graduate Job Search</h1>
            </div>
          </div>
        </div>
        <div class="find_container">
          <div class="container">
            <div class="row">
              <div class="col">
                <form>
                  <div class="form-row">
                    <div class="form-group col-lg-3">
                      <input type="text" class="form-control" id="inputPatientName" placeholder="Your Name" required>
                    </div>
                    <div class="form-group col-lg-3">
                      <select name="location" class="form-control wide" id="inputLocation" required>
                        <option value="">Select Location</option>
                        <option value="NCR">NCR</option>
                        <option value="CAR">CAR</option>
                        <option value="REGION1">ILOCOS REGION 1</option>
                        <option value="REGION2">CAGAYAN VALLEY REGION 2</option>
                        <option value="REGION3">CENTRAL LUZON REGION 3</option>
                        <option value="REGION4A">CALABARZON REGION 4 - A</option>
                        <option value="REGION4B">MIMAROPA REGION 4 - B</option>
                        <option value="REGION5">BICOL REGION 5</option>
                        <option value="REGION6">WESTERN VISAYAS REGION 6</option>
                        <option value="NIR">NEGROS ISLAND REGION (NIR)</option>
                        <option value="REGION7">CENTRAL VISAYAS REGION 7</option>
                        <option value="REGION8">EASTERN VISAYAS REGION 8</option>
                        <option value="REGION9">ZAMBOANGA PENINSULA REGION 9</option>
                        <option value="REGION10">NORTHERN MINDANAO REGION 10</option>
                        <option value="REGION11">DAVAO REGION 11</option>
                        <option value="REGION12">SOCCSKSARGEN REGION 12</option>
                        <option value="REGION13">CARAGA REGION 13</option>
                        <option value="BARMM">BARMM</option>
                      </select>
                    </div>
                    <div class="form-group col-lg-3">
                      <select name="position" class="form-control wide" id="inputPosition" required>
                        <option value="">Select Position</option>
                        <option value="web-designer">Web Designer</option>
                        <option value="web-developer">Web Developer</option>
                        <option value="graphic-designer">Graphic Designer</option>
                        <option value="content-writer">Content Writer</option>
                        <option value="encoder">Encoder</option>
                        <option value="accounting">Accounting</option>
                        <option value="creative-director">Creative Director</option>
                        <option value="sales-agent">Sales Agent</option>
                        <option value="content-creator">Content Creator</option>
                        <option value="others">Others</option>
                      </select>
                    </div>
                    <div class="form-group col-lg-3">
                      <div class="btn-box">
                        <button type="submit" class="btn">Submit Now</button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
            <ul class="job_check_list">
              <li>
                <input id="checkbox_qu_02" type="checkbox" class="styled-checkbox">
                <label for="checkbox_qu_02">Full Time</label>
              </li>
              <li>
                <input id="checkbox_qu_03" type="checkbox" class="styled-checkbox">
                <label for="checkbox_qu_03">Part Time</label>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </section>
    <!-- end slider section -->
  </div>

  <!-- job section -->
  <section class="job_section layout_padding">
    <div class="container">
      <div class="heading_container heading_center">
        <h2>FEATURED JOBS</h2>
      </div>
      <div class="job_container">
        <h4 class="job_heading">Featured Jobs</h4>
        <div class="row">
          <div class="col-lg-6">
            <div class="box">
              <div class="job_content-box">
                <div class="img-box">
                  <img src="../assets/images/NTC.jpg" alt="National Telecommunication Commission">
                </div>
                <div class="detail-box">
                  <h5>National Telecommunication Commission</h5>
                  <div class="detail-info">
                    <h6>
                      <i class="fa fa-map-marker" aria-hidden="true"></i>
                      <span>Manila</span>
                    </h6>
                    <h6>
                      <i class="fa fa-money" aria-hidden="true"></i>
                      <span>₱18,000 - ₱30,000</span>
                    </h6>
                  </div>
                </div>
              </div>
              <div class="option-box">
                <a href="NTCommission.html" class="apply-btn">Apply Now</a>
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="box">
              <div class="job_content-box">
                <div class="img-box">
                  <img src="../assets/images/NIA.jpg" alt="National Irrigation Administration">
                </div>
                <div class="detail-box">
                  <h5>National Irrigation Administration</h5>
                  <div class="detail-info">
                    <h6>
                      <i class="fa fa-map-marker" aria-hidden="true"></i>
                      <span>Metro Manila</span>
                    </h6>
                    <h6>
                      <i class="fa fa-money" aria-hidden="true"></i>
                      <span>₱20,000 - ₱35,000</span>
                    </h6>
                  </div>
                </div>
              </div>
              <div class="option-box">
                <a href="NIA.html" class="apply-btn">Apply Now</a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="job_container">
        <h4 class="job_heading">Jobs</h4>
        <div class="row">
          <div class="col-lg-6">
            <div class="box">
              <div class="job_content-box">
                <div class="img-box">
                  <img src="../assets/images/NPO.jpg" alt="National Printing Office">
                </div>
                <div class="detail-box">
                  <h5>National Printing Office</h5>
                  <div class="detail-info">
                    <h6>
                      <i class="fa fa-map-marker" aria-hidden="true"></i>
                      <span>Manila</span>
                    </h6>
                    <h6>
                      <i class="fa fa-money" aria-hidden="true"></i>
                      <span>₱15,000 - ₱39,000/mo</span>
                    </h6>
                  </div>
                </div>
              </div>
              <div class="option-box">
                <a href="NPO.html" class="apply-btn">Apply Now</a>
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="box">
              <div class="job_content-box">
                <div class="img-box">
                  <img src="../assets/images/Accenture.jpg" alt="Accenture">
                </div>
                <div class="detail-box">
                  <h5>Accenture</h5>
                  <div class="detail-info">
                    <h6>
                      <i class="fa fa-map-marker" aria-hidden="true"></i>
                      <span>Metro Manila</span>
                    </h6>
                    <h6>
                      <i class="fa fa-money" aria-hidden="true"></i>
                      <span>₱20,000 - ₱50,000</span>
                    </h6>
                  </div>
                </div>
              </div>
              <div class="option-box">
                <a href="Accenture.html" class="apply-btn">Apply Now</a>
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="box">
              <div class="job_content-box">
                <div class="img-box">
                  <img src="../assets/images/Teleperformance.jpg" alt="Teleperformance">
                </div>
                <div class="detail-box">
                  <h5>Teleperformance</h5>
                  <div class="detail-info">
                    <h6>
                      <i class="fa fa-map-marker" aria-hidden="true"></i>
                      <span>Mandaluyong</span>
                    </h6>
                    <h6>
                      <i class="fa fa-money" aria-hidden="true"></i>
                      <span>₱16,000 - ₱27,000</span>
                    </h6>
                  </div>
                </div>
              </div>
              <div class="option-box">
                <a href="Teleperformance.html" class="apply-btn">Apply Now</a>
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="box">
              <div class="job_content-box">
                <div class="img-box">
                  <img src="../assets/images/Alorica.jpg" alt="Alorica">
                </div>
                <div class="detail-box">
                  <h5>Alorica</h5>
                  <div class="detail-info">
                    <h6>
                      <i class="fa fa-map-marker" aria-hidden="true"></i>
                      <span>Metro Manila</span>
                    </h6>
                    <h6>
                      <i class="fa fa-money" aria-hidden="true"></i>
                      <span>₱15,000 - ₱22,000</span>
                    </h6>
                  </div>
                </div>
              </div>
              <div class="option-box">
                <a href="Alorica.html" class="apply-btn">Apply Now</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="btn-box">
        <a href="job.html">View All</a>
      </div>
    </div>
  </section>
  <!-- end job section -->

  <!-- expert section -->
  <section class="expert_section layout_padding">
    <div class="container">
      <div class="heading_container heading_center">
        <h2>Our Team</h2>
        <br>
      </div>
      <div class="container">
        <div class="row">
          <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <div class="our-team">
              <div class="picture">
                <img class="img-fluid" src="../assets/images/MAANDREA.png" alt="Ma. Andrea">
              </div>
              <div class="team-content">
                <h3 class="name">Ma. Andrea</h3>
                <h4 class="title">Programmer (Leader)</h4>
              </div>
              <ul class="social">
                <li><a href="https://www.facebook.com/maz.andrea.2024" class="fa fa-facebook" aria-hidden="true" target="_blank" rel="noopener"></a></li>
                <li><a href="https://www.instagram.com/mmxder" class="fa fa-instagram" aria-hidden="true" target="_blank" rel="noopener"></a></li>
              </ul>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <div class="our-team">
              <div class="picture">
                <img class="img-fluid" src="../assets/images/VINCEP.png" alt="Vince Peter">
              </div>
              <div class="team-content">
                <h3 class="name">Vince Peter</h3>
                <h4 class="title">Researcher</h4>
              </div>
              <ul class="social">
                <li><a href="https://www.facebook.com/vincepeter.ventivingo.1" class="fa fa-facebook" aria-hidden="true" target="_blank" rel="noopener"></a></li>
                <li><a href="https://www.instagram.com/vincepeterventivingo" class="fa fa-instagram" aria-hidden="true" target="_blank" rel="noopener"></a></li>
              </ul>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <div class="our-team">
              <div class="picture">
                <img class="img-fluid" src="../assets/images/FAITHANN.png" alt="Faith Ann">
              </div>
              <div class="team-content">
                <h3 class="name">Faith Ann</h3>
                <h4 class="title">Designer</h4>
              </div>
              <ul class="social">
                <li><a href="https://www.facebook.com/faithann.torres.94" class="fa fa-facebook" aria-hidden="true" target="_blank" rel="noopener"></a></li>
                <li><a href="https://www.instagram.com/faithanntorres" class="fa fa-instagram" aria-hidden="true" target="_blank" rel="noopener"></a></li>
              </ul>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <div class="our-team">
              <div class="picture">
                <img class="img-fluid" src="../assets/images/ADRIAN1.png" alt="Adrian">
              </div>
              <div class="team-content">
                <h3 class="name">Adrian</h3>
                <h4 class="title">Support</h4>
              </div>
              <ul class="social">
                <li><a href="https://www.facebook.com/adrian.dumlao.3386" class="fa fa-facebook" aria-hidden="true" target="_blank" rel="noopener"></a></li>
                <li><a href="https://www.instagram.com/adriandumlao2002" class="fa fa-instagram" aria-hidden="true" target="_blank" rel="noopener"></a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- end expert section -->

    <?php include_once '../includes/user_footer.php' ?>

    <script src="../assets/js/jquery-3.4.1.min.js"></script>
    <!-- bootstrap js -->
    <script src="../assets/js/bootstrap.js"></script>
    <!-- nice select -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js" integrity="sha256-Zr3vByTlMGQhvMfgkQ5BtWRSKBGa2QlspKYJnkjZTmo=" crossorigin="anonymous"></script>
    <!-- custom js -->
    <script src="../assets/js/custom.js"></script>
</body>
</html>