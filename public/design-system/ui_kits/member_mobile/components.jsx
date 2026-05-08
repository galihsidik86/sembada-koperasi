/* Sembada Anggota — mobile member app components
   Loaded as Babel-transpiled JSX. Exposes components to window for index.html. */

const { useState, useEffect } = React;

/* --------------- shared atoms --------------- */
const SmIcon = ({ d, size = 20, stroke = 1.5 }) => (
  <svg width={size} height={size} viewBox="0 0 24 24" fill="none"
       stroke="currentColor" strokeWidth={stroke}
       strokeLinecap="round" strokeLinejoin="round">{d}</svg>
);

const I = {
  home:     <><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></>,
  send:     <><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></>,
  receipt:  <><path d="M4 2v20l3-2 3 2 3-2 3 2 3-2V2H4z"/><line x1="8" y1="7" x2="16" y2="7"/><line x1="8" y1="11" x2="16" y2="11"/><line x1="8" y1="15" x2="13" y2="15"/></>,
  user:     <><circle cx="12" cy="8" r="4"/><path d="M4 21v-1a8 8 0 0 1 16 0v1"/></>,
  bell:     <><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></>,
  arrowR:   <><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></>,
  plus:     <><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></>,
  arrowDown:<><line x1="12" y1="5" x2="12" y2="19"/><polyline points="19 12 12 19 5 12"/></>,
  arrowUp:  <><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></>,
  zap:      <><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></>,
  shield:   <><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></>,
  loan:     <><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></>,
  bills:    <><rect x="4" y="3" width="16" height="18" rx="2"/><line x1="8" y1="8" x2="16" y2="8"/><line x1="8" y1="12" x2="16" y2="12"/><line x1="8" y1="16" x2="13" y2="16"/></>,
  shu:      <><path d="M12 2v20M5 9c2 0 4 1 7 4 3-3 5-4 7-4M5 14c2 0 4 1 7 4 3-3 5-4 7-4"/></>,
  chevR:    <><polyline points="9 18 15 12 9 6"/></>,
  back:     <><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></>,
  close:    <><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></>,
  check:    <><polyline points="20 6 9 17 4 12"/></>,
  search:   <><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></>,
};

/* --------------- App Header --------------- */
function AppHeader({ title, onBack, right }) {
  return (
    <div style={appHeaderStyle.bar}>
      {onBack ? (
        <button onClick={onBack} style={appHeaderStyle.iconBtn}><SmIcon d={I.back} /></button>
      ) : <div style={{width:40}}/>}
      <div style={appHeaderStyle.title}>{title}</div>
      <div style={{width:40, display:'flex', justifyContent:'flex-end'}}>{right}</div>
    </div>
  );
}
const appHeaderStyle = {
  bar: { height: 56, display:'flex', alignItems:'center', justifyContent:'space-between',
         padding:'0 8px', background:'rgba(245,241,232,0.92)', backdropFilter:'blur(8px)',
         borderBottom:'1px solid #ECE5D8', position:'sticky', top:0, zIndex:5 },
  title: { font:'700 17px "Plus Jakarta Sans", sans-serif', color:'#5C3A1E' },
  iconBtn: { width:40, height:40, border:'none', background:'transparent', color:'#5C3A1E',
             display:'flex', alignItems:'center', justifyContent:'center', cursor:'pointer', borderRadius:8 }
};

/* --------------- Bottom Nav --------------- */
function BottomNav({ active, onChange }) {
  const items = [
    { id:'home',  label:'Beranda',  icon:I.home },
    { id:'trf',   label:'Transfer', icon:I.send },
    { id:'rwt',   label:'Riwayat',  icon:I.receipt },
    { id:'profile', label:'Akun',   icon:I.user },
  ];
  return (
    <div style={bnStyle.bar}>
      {items.map(it => {
        const on = active === it.id;
        return (
          <button key={it.id} onClick={() => onChange(it.id)} style={bnStyle.btn}>
            <div style={{...bnStyle.icon, color: on ? '#8B2E2E' : '#8C6B4A'}}>
              <SmIcon d={it.icon} size={22} stroke={on ? 2 : 1.5}/>
            </div>
            <div style={{...bnStyle.lbl, color: on ? '#8B2E2E' : '#8C6B4A', fontWeight: on ? 700 : 500}}>
              {it.label}
            </div>
          </button>
        );
      })}
    </div>
  );
}
const bnStyle = {
  bar: { height: 64, display:'grid', gridTemplateColumns:'repeat(4,1fr)',
         background:'#FFFFFF', borderTop:'1px solid #ECE5D8',
         paddingBottom:'env(safe-area-inset-bottom, 0)' },
  btn: { border:'none', background:'transparent', display:'flex', flexDirection:'column',
         alignItems:'center', justifyContent:'center', gap:3, cursor:'pointer' },
  icon: { display:'flex' },
  lbl: { font:'500 11px "Plus Jakarta Sans", sans-serif' },
};

/* --------------- Balance Hero (the big sogan card with batik) --------------- */
function BalanceHero({ name = 'Bu Sari', total = 12420000 }) {
  const fmt = (n) => 'Rp ' + n.toLocaleString('id-ID');
  return (
    <div style={heroStyle.wrap}>
      <div style={heroStyle.batik}/>
      <div style={heroStyle.greet}>Halo, {name}</div>
      <div style={heroStyle.label}>Total simpanan kamu</div>
      <div style={heroStyle.amt}>{fmt(total)}</div>
      <div style={heroStyle.delta}>+ Rp 250.000 bulan ini</div>
      <div style={heroStyle.sub}>
        <div><span style={heroStyle.subLbl}>Pokok</span><span style={heroStyle.subVal}>Rp 500rb</span></div>
        <div style={heroStyle.divider}/>
        <div><span style={heroStyle.subLbl}>Wajib</span><span style={heroStyle.subVal}>Rp 4,2 jt</span></div>
        <div style={heroStyle.divider}/>
        <div><span style={heroStyle.subLbl}>Sukarela</span><span style={heroStyle.subVal}>Rp 7,7 jt</span></div>
      </div>
    </div>
  );
}
const heroStyle = {
  wrap: { background:'linear-gradient(140deg,#8B2E2E 0%,#6E2424 100%)', color:'#F5F1E8',
          borderRadius:16, padding:'20px 22px 18px', position:'relative', overflow:'hidden',
          boxShadow:'0 8px 24px rgba(92,58,30,0.16)' },
  batik:{ position:'absolute', inset:0,
          backgroundImage:"url('../../assets/patterns/kawung.svg')",
          backgroundSize:'80px 80px', opacity:0.08, pointerEvents:'none' },
  greet:{ font:'500 13px "Plus Jakarta Sans", sans-serif', color:'rgba(245,241,232,0.75)', position:'relative' },
  label:{ font:'500 12px "Plus Jakarta Sans", sans-serif', color:'rgba(245,241,232,0.7)',
          marginTop:14, position:'relative' },
  amt:  { font:'600 36px/1 "Fraunces", serif', fontVariationSettings:'"opsz" 72,"SOFT" 30',
          letterSpacing:'-0.02em', marginTop:6, position:'relative', fontFeatureSettings:'"tnum"' },
  delta:{ font:'600 12px "Plus Jakarta Sans", sans-serif', color:'#ECC979', marginTop:6, position:'relative' },
  sub:  { display:'flex', alignItems:'center', marginTop:16, gap:10, position:'relative' },
  subLbl:{ display:'block', font:'500 10px "Plus Jakarta Sans", sans-serif',
           color:'rgba(245,241,232,0.6)', textTransform:'uppercase', letterSpacing:'0.08em' },
  subVal:{ display:'block', font:'700 14px "Plus Jakarta Sans", sans-serif',
           color:'#F5F1E8', marginTop:2, fontFeatureSettings:'"tnum"' },
  divider:{ width:1, height:24, background:'rgba(245,241,232,0.18)' },
};

/* --------------- Quick Action Grid --------------- */
function QuickActions({ onAction }) {
  const acts = [
    { id:'setor',     lbl:'Setor',    icon:I.arrowDown, color:'#4A7C3A' },
    { id:'tarik',     lbl:'Tarik',    icon:I.arrowUp,   color:'#8B2E2E' },
    { id:'pinjaman',  lbl:'Pinjaman', icon:I.loan,      color:'#8B2E2E' },
    { id:'bayar',     lbl:'Cicilan',  icon:I.bills,     color:'#5C3A1E' },
    { id:'tagihan',   lbl:'Tagihan',  icon:I.zap,       color:'#D4A437' },
    { id:'shu',       lbl:'SHU',      icon:I.shu,       color:'#D4A437' },
    { id:'kartu',     lbl:'Kartu',    icon:I.shield,    color:'#5C3A1E' },
    { id:'lain',      lbl:'Lainnya',  icon:I.plus,      color:'#8C6B4A' },
  ];
  return (
    <div style={qaStyle.grid}>
      {acts.map(a => (
        <button key={a.id} onClick={() => onAction?.(a.id)} style={qaStyle.btn}>
          <div style={{...qaStyle.iconBg, color:a.color}}><SmIcon d={a.icon} size={22}/></div>
          <div style={qaStyle.lbl}>{a.lbl}</div>
        </button>
      ))}
    </div>
  );
}
const qaStyle = {
  grid:{ display:'grid', gridTemplateColumns:'repeat(4,1fr)', gap:12, padding:'0 4px' },
  btn: { display:'flex', flexDirection:'column', alignItems:'center', gap:6,
         border:'none', background:'transparent', cursor:'pointer', padding:'4px 0' },
  iconBg:{ width:48, height:48, background:'#FFFFFF', border:'1px solid #ECE5D8',
           borderRadius:14, display:'flex', alignItems:'center', justifyContent:'center',
           boxShadow:'0 1px 2px rgba(92,58,30,0.06)' },
  lbl: { font:'500 11px "Plus Jakarta Sans", sans-serif', color:'#5C3A1E' },
};

/* --------------- Loan Status Card --------------- */
function LoanStatusCard({ onClick }) {
  return (
    <div style={lsStyle.card} onClick={onClick}>
      <div style={lsStyle.top}>
        <div>
          <div style={lsStyle.eyebrow}>PINJAMAN BERJALAN</div>
          <div style={lsStyle.amt}>Rp 5.000.000</div>
        </div>
        <span style={lsStyle.badge}>3 / 12 bulan</span>
      </div>
      <div style={lsStyle.barWrap}>
        <div style={{...lsStyle.barFill, width:'25%'}}/>
      </div>
      <div style={lsStyle.row}>
        <span style={lsStyle.muted}>Jatuh tempo berikutnya</span>
        <span style={lsStyle.due}>15 Mei · Rp 458.000</span>
      </div>
    </div>
  );
}
const lsStyle = {
  card:{ background:'#FFFFFF', border:'1px solid #ECE5D8', borderRadius:12, padding:18,
         boxShadow:'0 2px 6px rgba(92,58,30,0.08)', cursor:'pointer' },
  top: { display:'flex', justifyContent:'space-between', alignItems:'flex-start' },
  eyebrow:{ font:'700 10px "Plus Jakarta Sans", sans-serif', letterSpacing:'0.12em',
            color:'#8B2E2E', textTransform:'uppercase' },
  amt: { font:'700 22px "Plus Jakarta Sans", sans-serif', color:'#1F1B16', marginTop:4,
         fontFeatureSettings:'"tnum"' },
  badge:{ font:'700 11px "Plus Jakarta Sans", sans-serif', color:'#97751F',
          background:'#FAEFC9', padding:'4px 10px', borderRadius:9999 },
  barWrap:{ background:'#ECE5D8', height:6, borderRadius:9999, marginTop:14, overflow:'hidden' },
  barFill:{ background:'#4A7C3A', height:'100%', borderRadius:9999 },
  row: { display:'flex', justifyContent:'space-between', alignItems:'center', marginTop:12 },
  muted:{ font:'500 12px "Plus Jakarta Sans", sans-serif', color:'#8C6B4A' },
  due: { font:'700 13px "Plus Jakarta Sans", sans-serif', color:'#5C3A1E', fontFeatureSettings:'"tnum"' },
};

/* --------------- Transaction List Item --------------- */
function TxItem({ kind, title, sub, amount, time }) {
  const isIn = kind === 'in';
  const color = isIn ? '#4A7C3A' : '#5C3A1E';
  const bg    = isIn ? '#E4F0DE' : '#F4DDD3';
  const sign  = isIn ? '+' : '−';
  return (
    <div style={txStyle.row}>
      <div style={{...txStyle.icon, background:bg, color:color}}>
        <SmIcon d={isIn ? I.arrowDown : I.arrowUp} size={18} stroke={2}/>
      </div>
      <div style={txStyle.body}>
        <div style={txStyle.title}>{title}</div>
        <div style={txStyle.sub}>{sub}</div>
      </div>
      <div style={txStyle.right}>
        <div style={{...txStyle.amt, color}}>{sign} {amount}</div>
        <div style={txStyle.time}>{time}</div>
      </div>
    </div>
  );
}
const txStyle = {
  row: { display:'flex', alignItems:'center', gap:12, padding:'12px 4px' },
  icon:{ width:38, height:38, borderRadius:10, display:'flex',
         alignItems:'center', justifyContent:'center', flexShrink:0 },
  body:{ flex:1, minWidth:0 },
  title:{ font:'600 14px "Plus Jakarta Sans", sans-serif', color:'#5C3A1E', overflow:'hidden',
          textOverflow:'ellipsis', whiteSpace:'nowrap' },
  sub:{ font:'500 12px "Plus Jakarta Sans", sans-serif', color:'#8C6B4A', marginTop:2 },
  right:{ textAlign:'right' },
  amt:{ font:'700 14px "Plus Jakarta Sans", sans-serif', fontFeatureSettings:'"tnum"' },
  time:{ font:'500 11px "Plus Jakarta Sans", sans-serif', color:'#B89A7A', marginTop:2 },
};

/* --------------- Section Header --------------- */
function SectionHeader({ title, action }) {
  return (
    <div style={shStyle.row}>
      <div style={shStyle.title}>{title}</div>
      {action && <button style={shStyle.action}>{action} ›</button>}
    </div>
  );
}
const shStyle = {
  row:{ display:'flex', alignItems:'center', justifyContent:'space-between', margin:'4px 4px 8px' },
  title:{ font:'700 16px "Plus Jakarta Sans", sans-serif', color:'#1F1B16' },
  action:{ font:'600 13px "Plus Jakarta Sans", sans-serif', color:'#8B2E2E',
           border:'none', background:'transparent', cursor:'pointer', padding:0 },
};

/* expose */
Object.assign(window, {
  SmIcon, I, AppHeader, BottomNav, BalanceHero,
  QuickActions, LoanStatusCard, TxItem, SectionHeader,
});
