import pandas as pd
import random
import os


mevcut_klasor = os.path.dirname(os.path.abspath(__file__))
ana_proje_klasoru = os.path.dirname(mevcut_klasor)
data_klasoru = os.path.join(ana_proje_klasoru, 'data')

if not os.path.exists(data_klasoru):
    os.makedirs(data_klasoru)


gunler = ['Pazartesi', 'Sali', 'Carsamba', 'Persembe', 'Cuma', 'Cumartesi', 'Pazar']
saatler = [f"{h:02d}:00" for h in range(7, 23)]
uyelikler = ['Standart', 'Ogrenci', 'VIP', 'Premium']
cinsiyetler = ['Erkek', 'Kadin']
bolgeler = ['Agirlik Istasyonu', 'Kardiyo', 'Yuzme Havuzu', 'Grup Stüdyosu', 'SPA & Sauna']

veri_listesi = []
for hafta in range(1, 5): 
    for gun in gunler:
        for saat in saatler:
            saat_int = int(saat.split(':')[0])
            for cinsiyet in cinsiyetler:
                for bolge in bolgeler:
                    kisi_sayisi = random.randint(1, 15)
                    if cinsiyet == 'Erkek' and bolge == 'Agirlik Istasyonu': kisi_sayisi += random.randint(5, 15)
                    if cinsiyet == 'Kadin' and bolge in ['Grup Stüdyosu', 'Kardiyo']: kisi_sayisi += random.randint(5, 15)
                    if 17 <= saat_int <= 20: kisi_sayisi += random.randint(5, 10)
                    if gun in ['Cumartesi', 'Pazar'] and bolge in ['Yuzme Havuzu', 'SPA & Sauna']: kisi_sayisi += random.randint(10, 20)
                    
                    memnuniyet = round(random.uniform(7.0, 10.0) - (kisi_sayisi * 0.05), 1)
                    if memnuniyet < 5: memnuniyet = 5.0
                    
                    secilen_uyelik = random.choice(uyelikler)
                    veri_listesi.append([gun, saat, cinsiyet, bolge, secilen_uyelik, kisi_sayisi, memnuniyet])

df_salon = pd.DataFrame(veri_listesi, columns=['gun', 'saat', 'cinsiyet', 'bolge', 'uyelik_tipi', 'uye_sayisi', 'memnuniyet'])
df_salon.to_csv(os.path.join(data_klasoru, 'salon_verisi.csv'), index=False)
print(f"✅ Salon Verisi: {len(df_salon)} satır kaydedildi.")



aktivite_seviyeleri = ['Hareketsiz', 'Az Hareketli', 'Orta Duzey', 'Sporcu']
yas_gruplari = ['18-25', '26-35', '36-50', '50+']

saglik_verisi = []

for _ in range(500):
    aktivite = random.choice(aktivite_seviyeleri)
    yas = random.choice(yas_gruplari)
    
    
    nabiz = random.randint(70, 90)
    uyku = random.uniform(5.5, 7.5)
    stres = random.randint(5, 9)
    su = random.uniform(1.0, 2.0)
    kalori = random.randint(1500, 2000)

   
    if aktivite == 'Sporcu':
        nabiz = random.randint(50, 65)      
        uyku = random.uniform(7.0, 9.0)     
        stres = random.randint(2, 5)        
        su = random.uniform(2.5, 4.0)       
        kalori = random.randint(2500, 3500) 
    
    elif aktivite == 'Orta Duzey':
        nabiz = random.randint(60, 75)
        uyku = random.uniform(6.5, 8.0)
        stres = random.randint(4, 7)
        su = random.uniform(2.0, 3.0)
        kalori = random.randint(2000, 2600)

    
    if yas == '50+':
        nabiz += 5
        su -= 0.3

    saglik_verisi.append([aktivite, yas, round(uyku, 1), stres, round(su, 1), nabiz, kalori])

df_saglik = pd.DataFrame(saglik_verisi, columns=['aktivite', 'yas_araligi', 'uyku_suresi', 'stres_seviyesi', 'su_tuketimi', 'nabiz', 'kalori_yakimi'])
df_saglik.to_csv(os.path.join(data_klasoru, 'saglik_verisi.csv'), index=False)
print(f"✅ Gelişmiş Sağlık Verisi: {len(df_saglik)} anket verisi oluşturuldu.")