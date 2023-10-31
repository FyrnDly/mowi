# Mengimpor pustaka yang diperlukan
from flask import Flask, request, jsonify
from PIL import Image
import torch
import torchvision

# Membuat objek aplikasi flask
app = Flask(__name__)

# Membuat transformasi untuk memproses gambar
transform = torchvision.transforms.Compose([
    torchvision.transforms.Resize((224, 224)), # Mengubah ukuran gambar menjadi 224x224 piksel
    torchvision.transforms.ToTensor(), # Mengubah gambar menjadi tensor
    torchvision.transforms.Normalize((0.5, 0.5, 0.5), (0.5, 0.5, 0.5)) # Menormalisasi tensor dengan mean dan std
])
# Membuat kelas untuk model cnn
class CNN(torch.nn.Module):
    def __init__(self):
        super(CNN, self).__init__()
        self.conv1 = torch.nn.Conv2d(3, 16, 3, 1, 1)
        self.relu = torch.nn.ReLU()
        self.maxpool = torch.nn.MaxPool2d(2, 2)
        self.conv2 = torch.nn.Conv2d(16, 32, 3, 1, 1)
        self.dropout = torch.nn.Dropout(0.25)
        self.fc1 = torch.nn.Linear(32*56*56, 128)
        self.fc2 = torch.nn.Linear(128, 3)

    def forward(self, x):
        x = self.conv1(x) 
        x = self.relu(x) 
        x = self.maxpool(x) 
        x = self.conv2(x) 
        x = self.relu(x) 
        x = self.maxpool(x) 
        x = self.dropout(x) 
        x = x.view(-1, 32*56*56) 
        x = self.fc1(x)
        x = self.relu(x)
        x = self.fc2(x)
        return x

# Membuat objek model cnn
model = CNN()
# Memuat model yang telah dilatih dari file .pt
state_dict = torch.load("model.pt")
model.load_state_dict(state_dict)
# Mengubah mode model menjadi eval
model.eval()

# Membuat route untuk API dengan metode POST
@app.route("/api/predict", methods=["POST"])
def predict():
    # Mendapatkan file gambar dari form
    image_file = request.files["image"]
    # Membuka file gambar dengan PIL
    image = Image.open(image_file)
    # Melakukan transformasi pada gambar sesuai dengan yang digunakan pada dataset
    image = transform(image)
    # Menambahkan dimensi batch pada gambar
    image = image.unsqueeze(0)
    # Melakukan prediksi dengan model
    output = model(image)
    # Mendapatkan probabilitas dan label kelas dari output
    prob, pred = torch.max(output, 1)
    # Mendapatkan nama kelas dari label kelas (sesuai dengan urutan folder pada dataset)
    classes = ["others", "unwell", "well"]
    class_name = classes[pred.item()]
    # Membuat dictionary untuk menyimpan hasil prediksi
    result = {
        "prob": prob.item(),
        "class": class_name
    }
    # Mengembalikan hasil prediksi dalam format JSON
    print(result)
    return jsonify(result)

# Menjalankan aplikasi flask jika file ini dijalankan secara langsung
if __name__ == "__main__":
    app.run(debug=True)
