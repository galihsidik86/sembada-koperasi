/* Sembada Anggota — screens (Home, Loan apply, History, Profile) */
const { useState: uS } = React;

function HomeScreen({ go }) {
  return (
    <div style={{padding:'12px 16px 80px', display:'flex', flexDirection:'column', gap:18}}>
      <BalanceHero name="Bu Sari" total={12420000}/>
      <QuickActions onAction={(id)=>{
        if(id==='pinjaman') go('apply');
        else if(id==='shu') go('shu');
      }}/>
      <SectionHeader title="Pinjaman kamu"/>
      <LoanStatusCard onClick={()=>go('loan')}/>
      <div style={{display:'flex', alignItems:'flex-start', gap:12, padding:14,
        background:'#FAEFC9', border:'1px solid #ECC979', borderRadius:12}}>
        <div style={{width:32,height:32,borderRadius:8,background:'#D4A437',color:'#5C3A1E',
          display:'flex',alignItems:'center',justifyContent:'center',flexShrink:0}}>
          <SmIcon d={I.shu} size={18} stroke={2}/>
        </div>
        <div style={{flex:1}}>
          <div style={{font:'700 14px "Plus Jakarta Sans"', color:'#5C3A1E'}}>SHU 2025 sudah cair</div>
          <div style={{font:'500 12px "Plus Jakarta Sans"', color:'#8C6B4A', marginTop:2,lineHeight:1.45}}>
            Bagian kamu Rp 1.180.000. Bisa dicairkan ke simpanan sukarela atau ditarik tunai.
          </div>
        </div>
      </div>
      <SectionHeader title="Transaksi terbaru" action="Lihat semua"/>
      <div style={{background:'#FFFFFF', border:'1px solid #ECE5D8', borderRadius:12, padding:'4px 14px',
        boxShadow:'0 2px 6px rgba(92,58,30,0.08)'}}>
        <TxItem kind="in"  title="Setor simpanan sukarela" sub="dari BCA · 5677" amount="Rp 500.000" time="Hari ini, 10:14"/>
        <div style={{height:1,background:'#ECE5D8'}}/>
        <TxItem kind="out" title="Cicilan pinjaman bulan 3"  sub="Otomatis"           amount="Rp 458.000" time="12 Mei"/>
        <div style={{height:1,background:'#ECE5D8'}}/>
        <TxItem kind="in"  title="Transfer dari Pak Joko"   sub="Sembada · *** 0921"  amount="Rp 250.000" time="10 Mei"/>
      </div>
    </div>
  );
}

function ApplyLoanScreen({ go }) {
  const [amount, setAmount] = uS(5000000);
  const [tenor,  setTenor]  = uS(12);
  const rate = 0.075;
  const monthly = Math.round(amount * (1 + rate*tenor/12) / tenor);
  const fmt = n => 'Rp ' + n.toLocaleString('id-ID');
  return (
    <div style={{padding:'4px 16px 100px'}}>
      <div style={{font:'500 13px "Plus Jakarta Sans"', color:'#8C6B4A', marginTop:8}}>Tujuan pinjaman</div>
      <div style={{display:'flex', gap:8, marginTop:10, flexWrap:'wrap'}}>
        {['Modal usaha','Pendidikan','Kesehatan','Renovasi','Lainnya'].map((t,i)=>(
          <button key={t} style={{
            padding:'8px 14px', borderRadius:9999,
            border:i===0 ? '1.5px solid #8B2E2E' : '1px solid #E5DDD0',
            background:i===0 ? '#FBF2EE' : '#FFFFFF',
            color:i===0 ? '#8B2E2E' : '#5C3A1E',
            font:`${i===0 ? 700 : 500} 13px "Plus Jakarta Sans"`,
            cursor:'pointer'
          }}>{t}</button>
        ))}
      </div>

      <div style={{font:'500 13px "Plus Jakarta Sans"', color:'#8C6B4A', marginTop:22}}>Nominal yang ingin kamu pinjam</div>
      <div style={{font:'600 36px/1 "Fraunces"', fontVariationSettings:'"opsz" 72', color:'#1F1B16',
        marginTop:6, fontFeatureSettings:'"tnum"'}}>{fmt(amount)}</div>
      <input type="range" min="500000" max="25000000" step="500000" value={amount}
        onChange={e=>setAmount(+e.target.value)}
        style={{width:'100%', accentColor:'#8B2E2E', marginTop:14}}/>
      <div style={{display:'flex', justifyContent:'space-between',
        font:'500 11px "Plus Jakarta Sans"', color:'#B89A7A', marginTop:4}}>
        <span>Rp 500rb</span><span>Maks Rp 25 jt</span>
      </div>

      <div style={{font:'500 13px "Plus Jakarta Sans"', color:'#8C6B4A', marginTop:22}}>Lama cicilan</div>
      <div style={{display:'flex', gap:8, marginTop:10}}>
        {[6,12,18,24].map(t=>(
          <button key={t} onClick={()=>setTenor(t)} style={{
            flex:1, padding:'10px 0', borderRadius:8,
            border:t===tenor ? '1.5px solid #8B2E2E' : '1px solid #E5DDD0',
            background:t===tenor ? '#FBF2EE' : '#FFFFFF',
            color:t===tenor ? '#8B2E2E' : '#5C3A1E',
            font:`${t===tenor ? 700 : 500} 14px "Plus Jakarta Sans"`,
            cursor:'pointer'
          }}>{t} bln</button>
        ))}
      </div>

      <div style={{background:'#FFFFFF', border:'1px solid #ECE5D8', borderRadius:12,
        padding:16, marginTop:24, boxShadow:'0 2px 6px rgba(92,58,30,0.08)'}}>
        <div style={{display:'flex', justifyContent:'space-between',
          font:'500 13px "Plus Jakarta Sans"', color:'#8C6B4A'}}>
          <span>Cicilan per bulan</span>
          <span style={{color:'#5C3A1E', fontWeight:700, fontFeatureSettings:'"tnum"'}}>{fmt(monthly)}</span>
        </div>
        <div style={{height:1,background:'#ECE5D8',margin:'10px 0'}}/>
        <div style={{display:'flex', justifyContent:'space-between',
          font:'500 13px "Plus Jakarta Sans"', color:'#8C6B4A'}}>
          <span>Bunga (jasa) per tahun</span>
          <span style={{color:'#5C3A1E', fontWeight:700}}>7,5%</span>
        </div>
        <div style={{height:1,background:'#ECE5D8',margin:'10px 0'}}/>
        <div style={{display:'flex', justifyContent:'space-between',
          font:'500 13px "Plus Jakarta Sans"', color:'#8C6B4A'}}>
          <span>Total dibayar</span>
          <span style={{color:'#5C3A1E', fontWeight:700, fontFeatureSettings:'"tnum"'}}>{fmt(monthly*tenor)}</span>
        </div>
      </div>

      <button onClick={()=>go('success')} style={{
        position:'fixed', left:16, right:16, bottom:'calc(env(safe-area-inset-bottom,0px) + 16px)',
        width:'calc(100% - 32px)', maxWidth:'calc(390px - 32px)',
        background:'#8B2E2E', color:'#F5F1E8', border:'none', borderRadius:12,
        padding:'16px', font:'700 16px "Plus Jakarta Sans"', cursor:'pointer',
        boxShadow:'0 6px 16px rgba(92,58,30,0.16)'
      }}>Ajukan pinjaman</button>
    </div>
  );
}

function SuccessScreen({ go }) {
  return (
    <div style={{padding:'40px 24px', textAlign:'center', display:'flex',
      flexDirection:'column', alignItems:'center', gap:18}}>
      <div style={{width:96, height:96, borderRadius:9999, background:'#E4F0DE',
        display:'flex', alignItems:'center', justifyContent:'center', color:'#4A7C3A'}}>
        <SmIcon d={I.check} size={56} stroke={2.5}/>
      </div>
      <div style={{font:'600 28px/1.15 "Fraunces"', fontVariationSettings:'"opsz" 48,"SOFT" 30',
        color:'#1F1B16', letterSpacing:'-0.01em', marginTop:6}}>
        Pengajuan kamu sudah masuk
      </div>
      <div style={{font:'500 15px/1.55 "Plus Jakarta Sans"', color:'#8C6B4A', maxWidth:300}}>
        Pengurus akan menghubungi kamu dalam 1×24 jam untuk verifikasi.
        Kamu bisa pantau status di menu Riwayat.
      </div>
      <div style={{background:'#FFFFFF', border:'1px solid #ECE5D8', borderRadius:12,
        padding:14, width:'100%', textAlign:'left', marginTop:12}}>
        <div style={{font:'500 12px "Plus Jakarta Sans"', color:'#8C6B4A'}}>Kode pengajuan</div>
        <div style={{font:'600 16px "JetBrains Mono"', color:'#5C3A1E', marginTop:2,
          letterSpacing:'0.04em'}}>PJM-2026-0512-AX9P</div>
      </div>
      <button onClick={()=>go('home')} style={{
        marginTop:24, width:'100%', background:'#8B2E2E', color:'#F5F1E8',
        border:'none', borderRadius:12, padding:'14px',
        font:'700 15px "Plus Jakarta Sans"', cursor:'pointer'
      }}>Kembali ke Beranda</button>
    </div>
  );
}

function HistoryScreen() {
  return (
    <div style={{padding:'8px 16px 80px'}}>
      <div style={{display:'flex', gap:8, padding:'8px 0 16px', overflow:'auto'}}>
        {['Semua','Setor','Tarik','Cicilan','Transfer'].map((t,i)=>(
          <button key={t} style={{
            padding:'7px 14px', borderRadius:9999,
            border:i===0 ? 'none' : '1px solid #E5DDD0',
            background:i===0 ? '#8B2E2E' : '#FFFFFF',
            color:i===0 ? '#F5F1E8' : '#5C3A1E',
            font:`${i===0 ? 700 : 500} 13px "Plus Jakarta Sans"`,
            cursor:'pointer', whiteSpace:'nowrap'
          }}>{t}</button>
        ))}
      </div>
      {[
        {date:'Hari ini · 6 Mei 2026', items:[
          {kind:'in',  title:'Setor simpanan sukarela', sub:'dari BCA · 5677',          amount:'Rp 500.000', time:'10:14'},
          {kind:'out', title:'Pulsa Telkomsel',         sub:'0812-xxxx-3344',           amount:'Rp 50.000',  time:'08:02'},
        ]},
        {date:'12 Mei 2026', items:[
          {kind:'out', title:'Cicilan pinjaman bulan 3', sub:'Otomatis',                amount:'Rp 458.000', time:'06:00'},
        ]},
        {date:'10 Mei 2026', items:[
          {kind:'in',  title:'Transfer dari Pak Joko',  sub:'Sembada · *** 0921',       amount:'Rp 250.000', time:'19:33'},
          {kind:'in',  title:'SHU 2025',                sub:'Pencairan tahunan',        amount:'Rp 1.180.000', time:'09:00'},
        ]},
      ].map(g => (
        <div key={g.date} style={{marginTop:8}}>
          <div style={{font:'700 11px "Plus Jakarta Sans"', letterSpacing:'0.08em',
            textTransform:'uppercase', color:'#8C6B4A', margin:'12px 4px 4px'}}>{g.date}</div>
          <div style={{background:'#FFFFFF', border:'1px solid #ECE5D8', borderRadius:12,
            padding:'4px 14px', boxShadow:'0 2px 6px rgba(92,58,30,0.08)'}}>
            {g.items.map((it,i)=>(
              <React.Fragment key={i}>
                {i>0 && <div style={{height:1,background:'#ECE5D8'}}/>}
                <TxItem {...it}/>
              </React.Fragment>
            ))}
          </div>
        </div>
      ))}
    </div>
  );
}

function ProfileScreen() {
  return (
    <div style={{padding:'8px 16px 80px'}}>
      <div style={{display:'flex', alignItems:'center', gap:14, padding:'12px 4px'}}>
        <div style={{width:64, height:64, borderRadius:9999, background:'#8B2E2E',
          color:'#F5F1E8', display:'flex', alignItems:'center', justifyContent:'center',
          font:'700 24px "Plus Jakarta Sans"'}}>SR</div>
        <div>
          <div style={{font:'700 18px "Plus Jakarta Sans"', color:'#1F1B16'}}>Sari Rahayu</div>
          <div style={{font:'500 13px "JetBrains Mono"', color:'#8C6B4A', marginTop:2}}>NA-00821 · KSP Sejahtera Cabang Solo</div>
        </div>
      </div>
      <div style={{background:'linear-gradient(135deg,#5C3A1E,#8B2E2E)', color:'#F5F1E8',
        borderRadius:16, padding:18, marginTop:14, position:'relative', overflow:'hidden',
        minHeight:170, display:'flex', flexDirection:'column', justifyContent:'space-between'}}>
        <div style={{position:'absolute', inset:0,
          backgroundImage:"url('../../assets/patterns/parang.svg')",
          backgroundSize:'120px', opacity:0.06}}/>
        <div style={{position:'relative'}}>
          <div style={{font:'700 10px "Plus Jakarta Sans"', letterSpacing:'0.12em',
            color:'rgba(245,241,232,0.7)', textTransform:'uppercase'}}>Kartu Anggota Digital</div>
          <div style={{font:'600 22px/1.1 "Fraunces"', marginTop:8}}>Sari Rahayu</div>
          <div style={{font:'500 13px "Plus Jakarta Sans"', color:'rgba(245,241,232,0.75)', marginTop:2}}>Anggota Aktif sejak Mar 2022</div>
        </div>
        <div style={{position:'relative', display:'flex', justifyContent:'space-between', alignItems:'flex-end'}}>
          <div>
            <div style={{font:'500 10px "Plus Jakarta Sans"', color:'rgba(245,241,232,0.6)', textTransform:'uppercase', letterSpacing:'0.08em'}}>No. Anggota</div>
            <div style={{font:'600 16px "JetBrains Mono"', color:'#F5F1E8', marginTop:2, letterSpacing:'0.04em'}}>NA-00821</div>
          </div>
          <img src="../../assets/logo/sembada-mark.svg" style={{width:40, height:40, opacity:0.85}}/>
        </div>
      </div>
      <div style={{background:'#FFFFFF', border:'1px solid #ECE5D8', borderRadius:12,
        marginTop:18, boxShadow:'0 2px 6px rgba(92,58,30,0.08)', overflow:'hidden'}}>
        {[
          {lbl:'Data pribadi', sub:'Nama, NIK, alamat'},
          {lbl:'Rekening pencairan', sub:'BCA · *** 5677'},
          {lbl:'Pengaturan keamanan', sub:'PIN, biometrik'},
          {lbl:'Pusat bantuan', sub:'FAQ, hubungi pengurus'},
        ].map((it,i,a)=>(
          <div key={it.lbl} style={{
            padding:'14px 16px', display:'flex', alignItems:'center', justifyContent:'space-between',
            borderTop: i===0 ? 'none' : '1px solid #ECE5D8', cursor:'pointer'
          }}>
            <div>
              <div style={{font:'600 14px "Plus Jakarta Sans"', color:'#5C3A1E'}}>{it.lbl}</div>
              <div style={{font:'500 12px "Plus Jakarta Sans"', color:'#8C6B4A', marginTop:2}}>{it.sub}</div>
            </div>
            <div style={{color:'#B89A7A'}}><SmIcon d={I.chevR} size={18}/></div>
          </div>
        ))}
      </div>
    </div>
  );
}

Object.assign(window, { HomeScreen, ApplyLoanScreen, SuccessScreen, HistoryScreen, ProfileScreen });
