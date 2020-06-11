<?php get_header(); ?>
<body id="narrative">
  <main>
    <section class="narrative-text one">
      <div class="container">
        <div class="headline-wrap">
          <p class="headline one" data-splitting>
            We find ourselves suspended in a vast cosmos.
          </p>
        </div>
      </div>
    </section>

    <section class="narrative-text two">
      <div class="container">
        <div class="headline-wrap">
          <p class="headline two" data-splitting>
            Separated by distance but connected by our creativity, <br/> we’re here to showcase the Seattle Central Creative Academy Class of 2020.
          </p>
        </div>
      </div>
    </section>

    <section id="our-planets">
      <a href="./index/">
        <div class="planets">
          <h2 class="headline">Start <em>Exploring →</em></h2>
          <div class="planet big-momma">
            <div class="interactive-gradient"></div>
            <div class="gradient gradient-51 spin"></div>
          </div>
          <div class="planet planet-one">
            <div class="interactive-gradient"></div>
            <div class="gradient gradient-<?php echo sprintf(
              '%02d',
              rand(1, 59)
            ); ?> spin"></div>
          </div>
          <div class="planet planet-two">
            <div class="interactive-gradient"></div>
            <div class="gradient gradient-<?php echo sprintf(
              '%02d',
              rand(1, 59)
            ); ?> spin"></div>
          </div>
          <div class="planet planet-three">
            <div class="interactive-gradient"></div>
            <div class="gradient gradient-<?php echo sprintf(
              '%02d',
              rand(1, 59)
            ); ?> spin"></div>
          </div>
          <div class="planet planet-four">
            <div class="interactive-gradient"></div>
            <div class="gradient gradient-<?php echo sprintf(
              '%02d',
              rand(1, 59)
            ); ?> spin"></div>
          </div>
          <div class="planet planet-five">
            <div class="interactive-gradient"></div>
            <div class="gradient gradient-<?php echo sprintf(
              '%02d',
              rand(1, 59)
            ); ?> spin"></div>
          </div>
        </div>
      </a>
    </section>
  </main>
</body>
<!-- </html> -->
<?php get_footer(); ?>
