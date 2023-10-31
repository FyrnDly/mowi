<img src="public/assets/logo.png" alt="Logi Mowi"><br>
Monitoring Smart System Sawi
===

## Summary
A website that uses a smart system that was developed using image processing to check the health condition of mustard leaves with the architecture algorithm CNN
### Other Information
- Product:  `Mowi`
- Developer:  [Fadly Ramdani](https://github.com/FyrnDly)
- Prototype: [Gdrive](https://drive.google.com/file/d/1AFUZ5ezsZJRpcQSN2zU00CHihznrqbSP/view?usp=drive_link)


## Install & Dependence
- python & pip
- php
- composer
### Packages Python
You can install it directly on your computer environment or use a virtual environment using [this method](https://www.freecodecamp.org/news/how-to-setup-virtual-environments-in-python/). Then use this command
```
pip install -r requirements.txt
```
or can also use this command
```
pip install pillow numpy matplotlib flask torch torchvision torchaudio
```
### Packages PHP
You can install vendor from composer with this command
```
composer install
```
## Use
### Run Website
```
php -S localhost:8000
```
### Run Api Flask

```
python rest_api.py
```
### Training Model
If you want to change the model data, you can follow these steps. But don't worry if the training results fail, you just need to copy model_backup.pt again so that the CNN model can run as before.
- change image on dataset model 
```
|—— ...
|—— main_ai
|    |—— dataset
|        |—— others
|            |—— ... (image model others)
|        |—— unwell
|            |—— ... (image model sawi unwell)
|        |—— well
|            |—— ... (image model sawi well)
|    |—— ...
```
- run training model
```
python main.py
```
