<?php require_once(sprintf("%s/ScrollStylerProcess.php", str_replace('/templates', '', dirname(__FILE__)))); ?>

<div class="scroll-styler">

    <div class="scroll-styler__container">

        <div class="scroll-styler__header">
            <div class="scroll-styler__header-title">Scroll Styler</div>
        </div>

        <div class="scroll-styler__body">

            <div class="scroll-styler__main">
                <form class="scroll-styler__form" action="" method="post">

                    <?php if (isset($_POST['scrollStylerOptionsEnabled'])) { ?>
                    <div class="scroll-styler__info-box-container scroll-styler__info-box-container--is-toggle">
                        <div class="scroll-styler__info-box scroll-styler__info-box--success">
                            <div class="scroll-styler__info-box-icon scroll-styler__info-box-icon--success"></div>
                            <div class="scroll-styler__info-box-desc"><?php echo $scrollStylerProcess->getLang('formSuccessDesc'); ?></div>
                        </div>
                    </div>
                    <?php } ?>

                    <div class="scroll-styler__form-info">
                        <span class="scroll-styler__form-info-icon"></span>
                        <?php echo $scrollStylerProcess->getLang('formDesc'); ?>
                    </div>

                    <div class="scroll-styler__form-row scroll-styler__form-row--px">
                        <div class="scroll-styler__form-label"><?php echo $scrollStylerProcess->getLang('scrollbarWidthLabel'); ?> <span class="scroll-styler__form-label-details"><?php echo $scrollStylerProcess->getLang('minLabel'); ?> 5px | <?php echo $scrollStylerProcess->getLang('maxLabel'); ?> 30px</span></div>
                        <input class="scroll-styler__form-control" id="scroll-styler-scrollbar-width" name="scrollbarWidth" type="number" min="5" max="30" value="<?php echo $scrollStylerProcess->data['scrollbarWidth']; ?>">
                    </div>

                    <div class="scroll-styler__form-row scroll-styler__form-row--px">
                        <div class="scroll-styler__form-label"><?php echo $scrollStylerProcess->getLang('scrollbarTrackPaddingLabel'); ?> <span class="scroll-styler__form-label-details"><?php echo $scrollStylerProcess->getLang('minLabel'); ?> 0px | <?php echo $scrollStylerProcess->getLang('maxLabel'); ?> 5px</span></div>
                        <input class="scroll-styler__form-control" id="scroll-styler-scrollbar-track-padding" name="scrollbarTrackPadding" type="number" min="0" max="5" value="<?php echo $scrollStylerProcess->data['scrollbarTrackPadding']; ?>">
                    </div>

                    <div class="scroll-styler__form-row">
                        <div class="scroll-styler__form-label"><?php echo $scrollStylerProcess->getLang('scrollbarTrackBgColorLabel'); ?></div>
                        <input class="scroll-styler__form-control js-minicolors" id="scroll-styler-scrollbar-track-background-color" name="scrollbarTrackBgColor" type="text" maxlength="7" value="<?php echo $scrollStylerProcess->data['scrollbarTrackBgColor']; ?>">
                    </div>

                    <div class="scroll-styler__form-row">
                        <div class="scroll-styler__form-label"><?php echo $scrollStylerProcess->getLang('scrollbarThumbBgColorLabel'); ?></div>
                        <input class="scroll-styler__form-control js-minicolors" id="scroll-styler-scrollbar-thumb-background-color" name="scrollbarThumbBgColor" type="text" maxlength="25" data-format="rgb" data-opacity="true" value="<?php echo $scrollStylerProcess->data['scrollbarThumbBgColor']; ?>">
                    </div>

                    <div class="scroll-styler__form-row">
                        <div class="scroll-styler__form-label"><?php echo $scrollStylerProcess->getLang('scrollbarThumbBgColorHoverLabel'); ?></div>
                        <input class="scroll-styler__form-control js-minicolors" id="scroll-styler-scrollbar-thumb-background-color-hover" name="scrollbarThumbBgColorHover" type="text" maxlength="25" data-format="rgb" data-opacity="true" value="<?php echo $scrollStylerProcess->data['scrollbarThumbBgColorHover']; ?>">
                    </div>

                    <div class="scroll-styler__form-row">
                        <div class="scroll-styler__form-label"><?php echo $scrollStylerProcess->getLang('scrollbarThumbBgColorActiveLabel'); ?></div>
                        <input class="scroll-styler__form-control js-minicolors" id="scroll-styler-scrollbar-thumb-background-color-active" name="scrollbarThumbBgColorActive" type="text" maxlength="25" data-format="rgb" data-opacity="true" value="<?php echo $scrollStylerProcess->data['scrollbarThumbBgColorActive']; ?>">
                    </div>

                    <div class="scroll-styler__form-row scroll-styler__form-row--px">
                        <div class="scroll-styler__form-label"><?php echo $scrollStylerProcess->getLang('scrollbarThumbBorderRadiusLabel'); ?> <span class="scroll-styler__form-label-details"><?php echo $scrollStylerProcess->getLang('minLabel'); ?> 0px | <?php echo $scrollStylerProcess->getLang('maxLabel'); ?> 15px</span></div>
                        <input class="scroll-styler__form-control" id="scroll-styler-scrollbar-thumb-border-radius" name="scrollbarThumbBorderRadius" type="number" min="0" max="15" value="<?php echo $scrollStylerProcess->data['scrollbarThumbBorderRadius']; ?>">
                    </div>

                    <div class="scroll-styler__form-row scroll-styler__form-row--btn-bar">
                        <input type="hidden" name="scrollStylerOptionsEnabled" value="true">
                        <button class="scroll-styler__btn" type="submit" title="<?php echo $scrollStylerProcess->getLang('saveButtonText'); ?>"><?php echo $scrollStylerProcess->getLang('saveButtonText'); ?></button>
                    </div>
                </form>
            </div>
            
            <div class="scroll-styler__aside">
                <div class="scroll-styler__promo">
                    <a class="scroll-styler__promo-link scroll-styler__promo-link--eav" href="https://1.envato.market/ag5YM" title="Elegant Age Verification – Responsive age-gate plugin for WordPress" target="_blank">
                        <span class="scroll-styler__promo-icon scroll-styler__promo-icon--1"></span>
                    </a>
                </div>
                <div class="scroll-styler__promo">
                    <a class="scroll-styler__promo-link scroll-styler__promo-link--gdpr" href="https://1.envato.market/50vqn" title="GDPR Cookie Law – Responsive jQuery GDPR Cookie Compliance Plugin" target="_blank">
                        <span class="scroll-styler__promo-icon scroll-styler__promo-icon--2"></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>