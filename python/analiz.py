import pandas as pd
import matplotlib.pyplot as plt
import os
import matplotlib.cm as cm
import numpy as np


script_dir = os.path.dirname(os.path.abspath(__file__))
ana_klasor = os.path.dirname(script_dir)
veri_dosyasi = os.path.join(ana_klasor, 'data', 'salon_verisi.csv')
resim_klasoru = os.path.join(ana_klasor, 'resimler')

if not os.path.exists(resim_klasoru):
    os.makedirs(resim_klasoru)

df = pd.read_csv(veri_dosyasi)


plt.figure(figsize=(5, 4))
gun_sirasi = ['Pazartesi', 'Sali', 'Carsamba', 'Persembe', 'Cuma', 'Cumartesi', 'Pazar']
df['gun'] = pd.Categorical(df['gun'], categories=gun_sirasi, ordered=True)
haftalik = df.groupby('gun', observed=False)['uye_sayisi'].sum()
plt.bar(haftalik.index, haftalik.values, color='#2C7A7B')
plt.title('Haftalık Toplam Giriş', fontsize=11, fontweight='bold')
plt.xticks(rotation=45, fontsize=8)
plt.tight_layout()
plt.savefig(os.path.join(resim_klasoru, 'analiz_grafigi.png'), dpi=100)
plt.close()


plt.figure(figsize=(5, 4))
saatlik = df.groupby('saat')['uye_sayisi'].sum()
plt.plot(saatlik.index, saatlik.values, marker='o', color='#D35400')
plt.title('Saatlik Genel Yoğunluk', fontsize=11, fontweight='bold')
plt.xticks(ticks=range(0, len(saatlik), 2), rotation=45, fontsize=8)
plt.grid(True, alpha=0.3)
plt.tight_layout()
plt.savefig(os.path.join(resim_klasoru, 'analiz_saatlik.png'), dpi=100)
plt.close()


plt.figure(figsize=(5, 4))
cinsiyet = df.groupby('cinsiyet')['uye_sayisi'].sum()
colors_gender = ['#3498db', '#e91e63']
plt.pie(cinsiyet, labels=cinsiyet.index, autopct='%1.1f%%', colors=colors_gender, startangle=90)
plt.title('Cinsiyet Dağılımı', fontsize=11, fontweight='bold')
plt.tight_layout()
plt.savefig(os.path.join(resim_klasoru, 'analiz_cinsiyet.png'), dpi=100)
plt.close()


plt.figure(figsize=(5, 4))
memnuniyet = df.groupby('bolge')['memnuniyet'].mean().sort_values()


norm = plt.Normalize(5, 10) 
colors = cm.RdYlGn(norm(memnuniyet.values))

bars = plt.barh(memnuniyet.index, memnuniyet.values, color=colors)
plt.title('Bölge Bazlı Memnuniyet (1-10)', fontsize=11, fontweight='bold')
plt.xlabel('Ortalama Puan')
plt.xlim(0, 10) 


for bar in bars:
    width = bar.get_width()
    plt.text(width - 1.5, bar.get_y() + bar.get_height()/2, f'{width:.1f}', 
             va='center', color='white', fontweight='bold')

plt.tight_layout()
plt.savefig(os.path.join(resim_klasoru, 'analiz_memnuniyet.png'), dpi=100)
plt.close()

print("✅ Grafikler güncellendi! (Memnuniyet analizi eklendi)")