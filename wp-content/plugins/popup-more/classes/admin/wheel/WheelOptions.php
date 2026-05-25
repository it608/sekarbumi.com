<?php

class WheelOptionsRenderer {
    private $wheelOptions;
    private $isFree;

    public function __construct($options = []) {
        $this->wheelOptions = $options;
        $this->isFree = ypm_is_free(); // Check if the plugin is in free mode
    }

    public function render() {
        ob_start();
        ?>
        <style>
            .wheel-options-container {
                font-family: Arial, sans-serif;
                max-width: 800px;
                margin: 0 auto;
            }
            #wheel-options-list {
                list-style: none;
                padding: 0;
            }
            .wheel-option {
                display: grid;
                grid-template-columns: 2fr 1fr 1fr 1fr 2fr auto;
                gap: 10px;
                align-items: center;
                padding: 10px;
                border: 1px solid #ddd;
                margin-bottom: 10px;
                border-radius: 5px;
                background-color: #f9f9f9;
            }
            .wheel-option input {
                padding: 8px;
                border: 1px solid #ccc;
                border-radius: 4px;
                width: 100%;
            }
            .wheel-option input[type="color"] {
                padding: 3px;
                height: 40px;
            }
            .wheel-option input[type="number"] {
                width: 80px;
            }
            .wheel-option .delete-option {
                background-color: #ff4d4d;
                color: white;
                border: none;
                padding: 8px 12px;
                border-radius: 4px;
                cursor: pointer;
            }
            .wheel-option .delete-option:hover {
                background-color: #cc0000;
            }
            .add-option-button {
                background-color: #4CAF50;
                color: white;
                border: none;
                padding: 10px 20px;
                border-radius: 4px;
                cursor: pointer;
                margin-top: 10px;
            }
            .add-option-button:hover {
                background-color: #45a049;
            }
            .column-label {
                font-weight: bold;
                margin-bottom: 5px;
                display: block;
            }
            .disabled-button {
                opacity: 0.5;
                cursor: not-allowed;
                color: white;
            }
        </style>

        <div class="wheel-options-container">
            <ul id="wheel-options-list">
                <li class="wheel-option column-labels">
                    <div><span class="column-label">Label</span></div>
                    <div><span class="column-label">Background Color</span></div>
                    <div><span class="column-label">Text Color</span></div>
                    <div><span class="column-label">Probability</span></div>
                    <div><span class="column-label">Prize</span></div>
                    <div><span class="column-label">Action</span></div>
                </li>
                <?php foreach ($this->wheelOptions as $key => $option): ?>
                    <?php
                    $label = htmlspecialchars($option['label'] ?? '');
                    $color = htmlspecialchars($option['color'] ?? '#ffffff');
                    $textColor = htmlspecialchars($option['textColor'] ?? '#000000');
                    $probability = htmlspecialchars($option['probability'] ?? '10');
                    $prize = htmlspecialchars($option['prize'] ?? 'No Prize');
                    ?>
                    <li class="wheel-option" data-key="<?php echo esc_attr($key); ?>">
                        <div>
                            <input type="text" name="ypm-wheeloptions[<?php echo esc_attr($key); ?>][label]" value="<?php echo esc_attr($label); ?>" placeholder="Label" />
                        </div>
                        <div>
                            <input type="color" name="ypm-wheeloptions[<?php echo esc_attr($key); ?>][color]" value="<?php echo esc_attr($color); ?>" />
                        </div>
                        <div>
                            <input type="color" name="ypm-wheeloptions[<?php echo esc_attr($key); ?>][textColor]" value="<?php echo esc_attr($textColor); ?>" />
                        </div>
                        <div>
                            <input type="number" name="ypm-wheeloptions[<?php echo esc_attr($key); ?>][probability]" value="<?php echo esc_attr($probability); ?>" min="1" max="100" class="ypm-prize-probability" placeholder="Probability" />
                        </div>
                        <div>
                            <input type="text" name="ypm-wheeloptions[<?php echo esc_attr($key); ?>][prize]" value="<?php echo esc_attr($prize); ?>" placeholder="Prize" />
                        </div>
                        <div>
                            <button type="button" class="delete-option <?php echo $this->isFree ? 'disabled-button' : ''; ?>" 
                                >
                                Delete
                                <?php echo $this->isFree ? '(Pro)' : ''; ?>
                            </button>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>

            <button type="button" class="add-option-button <?php echo $this->isFree ? 'disabled-button' : ''; ?>" id="add-option">
                Add Option
                <?php echo $this->isFree ? '(Pro)' : ''; ?>
            </button>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function () {
            const list = document.getElementById('wheel-options-list');
            const addButton = document.getElementById('add-option');
            let index = <?php echo count($this->wheelOptions); ?>;
            const isFree = <?php echo json_encode($this->isFree); ?>;

            if (!isFree) {
                addButton.addEventListener('click', function() {
                    const newItem = document.createElement('li');
                    newItem.className = 'wheel-option';
                    newItem.setAttribute('data-key', index);

                    newItem.innerHTML = `
                        <div>
                            <input type="text" name="ypm-wheeloptions[${index}][label]" placeholder="Label" />
                        </div>
                        <div>
                            <input type="color" name="ypm-wheeloptions[${index}][color]" value="#ffffff" />
                        </div>
                        <div>
                            <input type="color" name="ypm-wheeloptions[${index}][textColor]" value="#000000" />
                        </div>
                        <div>
                            <input type="number" name="ypm-wheeloptions[${index}][probability]" value="10" min="1" max="100" class="ypm-prize-probability" placeholder="Probability" />
                        </div>
                        <div>
                            <input type="text" name="ypm-wheeloptions[${index}][prize]" placeholder="Prize" />
                        </div>
                        <div>
                            <button type="button" class="delete-option">Delete</button>
                        </div>
                    `;
                    list.appendChild(newItem);
                    index++;
                });

                list.addEventListener('click', function (e) {
                    if (e.target.classList.contains('delete-option')) {
                        const option = e.target.closest('.wheel-option');
                        if (!option.classList.contains('column-labels')) {
                            option.remove();
                        }
                    }
                });
            }
        });
        </script>
        <?php

        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
}
?>
