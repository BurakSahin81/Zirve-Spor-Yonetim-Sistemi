import pandas as pd
import matplotlib.pyplot as plt
import os


script_dir = os.path.dirname(os.path.abspath(__file__))
ana_klasor = os.path.dirname(script_dir)
veri_dosyasi = os.path.join(ana_klasor, 'data', 'saglik_verisi.csv')
resim_klasoru = os.path.join(ana_klasor, 'resimler')

if not os.path.exists(resim_klasoru):
    os.makedirs(resim_klasoru)

df = pd.read_csv(veri_dosyasi)


ozet = df.groupby('aktivite')[['uyku_suresi', 'stres_seviyesi']].mean().sort_values('uyku_suresi')

fig, ax1 = plt.subplots(figsize=(6, 4))

color = '#2C7A7B'
ax1.set_xlabel('Aktivite Düzeyi')
ax1.set_ylabel('Uyku Süresi (Saat)', color=color, fontweight='bold')
ax1.bar(ozet.index, ozet['uyku_suresi'], color=color, alpha=0.6, label='Uyku')
ax1.tick_params(axis='y', labelcolor=color)

ax2 = ax1.twinx()  
color = '#e74c3c'
ax2.set_ylabel('Stres Seviyesi (1-10)', color=color, fontweight='bold')
ax2.plot(ozet.index, ozet['stres_seviyesi'], color=color, marker='o', linewidth=3, label='Stres')
ax2.tick_params(axis='y', labelcolor=color)

plt.title('Sporun Uyku ve Stres Üzerine Etkisi', fontsize=11, fontweight='bold')
plt.tight_layout()
plt.savefig(os.path.join(resim_klasoru, 'analiz_uyku_stres.png'), dpi=100)
plt.close()



su_ozet = df.groupby('aktivite')['su_tuketimi'].mean().sort_values()
plt.figure(figsize=(6, 4))
colors = ['#AED6F1', '#5DADE2', '#2E86C1', '#1B4F72']
plt.bar(su_ozet.index, su_ozet.values, color=colors)
plt.title('Günlük Ortalama Su Tüketimi (Litre)', fontsize=11, fontweight='bold')
plt.ylabel('Litre')
plt.grid(axis='y', linestyle='--', alpha=0.3)
plt.tight_layout()
plt.savefig(os.path.join(resim_klasoru, 'analiz_su.png'), dpi=100)
plt.close()



yas_nabiz = df.groupby('yas_araligi')['nabiz'].mean().sort_index()
plt.figure(figsize=(6, 4))
plt.plot(yas_nabiz.index, yas_nabiz.values, marker='s', linestyle='--', color='#8E44AD', linewidth=2)
plt.fill_between(yas_nabiz.index, yas_nabiz.values, 50, color='#8E44AD', alpha=0.1)
plt.title('Yaş Gruplarına Göre Ortalama Nabız', fontsize=11, fontweight='bold')
plt.ylabel('BPM (Atım/dk)')
plt.grid(True, alpha=0.3)
plt.tight_layout()
plt.savefig(os.path.join(resim_klasoru, 'analiz_yas_nabiz.png'), dpi=100)
plt.close()

print("✅ Yeni sağlık grafikleri (Uyku/Stres, Su, Yaş/Nabız) oluşturuldu!")