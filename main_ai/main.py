# Mengimpor pustaka yang diperlukan
import torch
import torchvision
import numpy as np
import matplotlib.pyplot as plt
from PIL import Image

# Menentukan direktori dataset
data_dir = "dataset/"

# Membuat transformasi untuk memproses gambar
transform = torchvision.transforms.Compose([
    torchvision.transforms.Resize((224, 224)), # Mengubah ukuran gambar menjadi 224x224 piksel
    torchvision.transforms.ToTensor(), # Mengubah gambar menjadi tensor
    torchvision.transforms.Normalize((0.5, 0.5, 0.5), (0.5, 0.5, 0.5)) # Menormalisasi tensor dengan mean dan std
])

# Membuat dataset dari direktori gambar dengan transformasi
dataset = torchvision.datasets.ImageFolder(data_dir, transform=transform)

# Membagi dataset menjadi data latih (80%) dan data uji (20%)
train_size = int(0.8 * len(dataset))
test_size = len(dataset) - train_size
train_dataset, test_dataset = torch.utils.data.random_split(dataset, [train_size, test_size])

# Membuat data loader untuk memuat data latih dan data uji dengan batch size 32
train_loader = torch.utils.data.DataLoader(train_dataset, batch_size=32, shuffle=True)
test_loader = torch.utils.data.DataLoader(test_dataset, batch_size=32, shuffle=False)

# Membuat kelas untuk model cnn
class CNN(torch.nn.Module):
    def __init__(self):
        super(CNN, self).__init__()
        # Membuat lapisan konvolusi pertama dengan 3 channel input dan 16 channel output, kernel size 3x3, stride 1, dan padding 1
        self.conv1 = torch.nn.Conv2d(3, 16, 3, 1, 1)
        # Membuat lapisan aktivasi ReLU
        self.relu = torch.nn.ReLU()
        # Membuat lapisan pooling maksimum dengan kernel size 2x2 dan stride 2
        self.maxpool = torch.nn.MaxPool2d(2, 2)
        # Membuat lapisan konvolusi kedua dengan 16 channel input dan 32 channel output, kernel size 3x3, stride 1, dan padding 1
        self.conv2 = torch.nn.Conv2d(16, 32, 3, 1, 1)
        # Membuat lapisan dropout dengan probabilitas 0.25
        self.dropout = torch.nn.Dropout(0.25)
        # Membuat lapisan linear pertama dengan 32*56*56 input dan 128 output
        self.fc1 = torch.nn.Linear(32*56*56, 128)
        # Membuat lapisan linear kedua dengan 128 input dan 3 output (sesuai dengan jumlah kelas)
        self.fc2 = torch.nn.Linear(128, 3)

    def forward(self, x):
        # Melakukan operasi forward pada input x
        x = self.conv1(x) # Melakukan konvolusi pertama
        x = self.relu(x) # Melakukan aktivasi ReLU
        x = self.maxpool(x) # Melakukan pooling maksimum
        x = self.conv2(x) # Melakukan konvolusi kedua
        x = self.relu(x) # Melakukan aktivasi ReLU
        x = self.maxpool(x) # Melakukan pooling maksimum
        x = self.dropout(x) # Melakukan dropout
        x = x.view(-1, 32*56*56) # Mengubah bentuk tensor menjadi vektor
        x = self.fc1(x) # Melakukan linear pertama
        x = self.relu(x) # Melakukan aktivasi ReLU
        x = self.fc2(x) # Melakukan linear kedua
        return x # Mengembalikan output

# Membuat objek model cnn
model = CNN()

# Membuat objek optimizer Adam dengan learning rate 0.001
optimizer = torch.optim.Adam(model.parameters(), lr=0.001)

# Membuat objek loss function CrossEntropyLoss
criterion = torch.nn.CrossEntropyLoss()

# Menentukan jumlah epoch
num_epochs = 10

# Melakukan pelatihan model
for epoch in range(num_epochs):
    # Menginisialisasi nilai loss dan akurasi
    train_loss = 0.0
    train_acc = 0.0
    # Mengubah mode model menjadi train
    model.train()
    # Melakukan iterasi pada data latih
    for inputs, labels in train_loader:
        # Menghapus gradient yang ada
        optimizer.zero_grad()
        # Melakukan prediksi dengan model
        outputs = model(inputs)
        # Menghitung loss dengan loss function
        loss = criterion(outputs, labels)
        # Melakukan backpropagation untuk menghitung gradient
        loss.backward()
        # Melakukan update parameter dengan optimizer
        optimizer.step()
        # Menambahkan nilai loss
        train_loss += loss.item()
        # Menghitung akurasi dengan membandingkan prediksi dan label
        _, preds = torch.max(outputs, 1)
        train_acc += torch.sum(preds == labels).item()
    # Menghitung rata-rata loss dan akurasi per epoch
    train_loss = train_loss / len(train_loader)
    train_acc = train_acc / len(train_dataset)
    # Menampilkan hasil pelatihan per epoch
    print(f"Epoch {epoch+1}, Train Loss: {train_loss:.4f}, Train Acc: {train_acc:.4f}")

# Menginisialisasi nilai loss dan akurasi untuk data uji
test_loss = 0.0
test_acc = 0.0
# Mengubah mode model menjadi eval
model.eval()
# Melakukan iterasi pada data uji
for inputs, labels in test_loader:
    # Melakukan prediksi dengan model
    outputs = model(inputs)
    # Menghitung loss dengan loss function
    loss = criterion(outputs, labels)
    # Menambahkan nilai loss
    test_loss += loss.item()
    # Menghitung akurasi dengan membandingkan prediksi dan label
    _, preds = torch.max(outputs, 1)
    test_acc += torch.sum(preds == labels).item()
# Menghitung rata-rata loss dan akurasi untuk data uji
test_loss = test_loss / len(test_loader)
test_acc = test_acc / len(test_dataset)
# Menampilkan hasil evaluasi pada data uji
print(f"Test Loss: {test_loss:.4f}, Test Acc: {test_acc:.4f}")
torch.save(model.state_dict(), "model.pt")


# Membuat fungsi prediksi untuk gambar baru
def predict(image_path):
    # Membuka gambar dari path yang diberikan
    image = Image.open(image_path)
    # Menampilkan gambar
    plt.imshow(image)
    plt.show()
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
    # Menampilkan probabilitas dan nama kelas 
    print(f"Prob: {prob.item():.4f}, Class: {class_name}")

predict('sawi_sehat.jpg')
print('Sawi Sehat')
predict('mobil.jpg')
print('Mobil')
predict('sawi_tidak_sehat.jpg')
print('Sawi Jelek')
